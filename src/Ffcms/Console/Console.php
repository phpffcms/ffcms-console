<?php 

namespace Ffcms\Console;

use Ffcms\Core\Exception\NativeException;
use Ffcms\Core\Helper\Type\Obj;
use Ffcms\Core\Helper\Type\Str;
use Ffcms\Core\Properties;
use Ffcms\Console\Transfer\Input;
use Ffcms\Console\Transfer\Output;
use \Illuminate\Database\Capsule\Manager as Capsule;

class Console
{
    /** @var \Ffcms\Core\Properties */
	public static $Properties;
    /** @var \Ffcms\Console\Transfer\Input */
    public static $Input;
    /** @var \Ffcms\Console\Transfer\Output */
    public static $Output;
    /** @var \Illuminate\Database\Capsule\Manager */
    public static $Database;

    /**
     * Build console entry point
     * @param array|null $services
     * @throws \Ffcms\Core\Exception\NativeException
     */
	public static function init(array $services = null)
	{
		self::$Properties = new Properties();
        self::$Input = new Input();
        self::$Output = new Output();

        // establish database link
        if (Obj::isArray(self::$Properties->get('database')) && (isset($services['Database']) && $services['Database'] === true || $services === null)) {
            self::$Database = new Capsule;
            self::$Database->addConnection(self::$Properties->get('database'));

            // Make this Capsule instance available globally via static methods... (optional)
            self::$Database->setAsGlobal();

            // Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
            self::$Database->bootEloquent();
        }
	}

    /**
     * Build console controllers.
     * php console.php Controller/Action index
     */
    public static function run()
    {
        global $argv;
        $output = null;
        if (!Obj::isArray($argv) || Str::likeEmpty($argv[1])) {
            $output = 'Console command is unknown! Type "console main/help" to get help guide';
        } else {
            $controller_action = $argv[1];
            $arrInput = explode('/', $controller_action);
            $controller = ucfirst(strtolower($arrInput[0]));
            $action = ucfirst(strtolower($arrInput[1]));
            if($action == null) {
                $action = 'Index';
            }
            // set action and id
            $action = 'action' . $action;
            $id = null;
            if (isset($argv[2])) {
                $id = $argv[2];
            }

            try {
                $controller_path = '/Apps/Controller/' . env_name . '/' . $controller . '.php';
                if(file_exists(root . $controller_path) && is_readable(root . $controller_path)) {
                    include_once(root . $controller_path);
                    $cname = 'Apps\Controller\\' . env_name . '\\' . $controller;
                    if(class_exists($cname)) {
                        $load = new $cname;
                        if(method_exists($cname, $action)) {
                            if($id !== null) {
                                $output = @$load->$action($id);
                            } else {
                                $output = @$load->$action();
                            }
                        } else {
                            throw new NativeException('Method ' . $action . '() not founded in ' . $cname . ' in file {root}' . $controller_path);
                        }
                        unset($load);
                    } else {
                        throw new NativeException('Namespace\\Class - ' . $cname . ' not founded in {root}' . $controller_path);
                    }
                } else {
                    throw new NativeException('Controller not founded: {root}' . $controller_path);
                }
            } catch(NativeException $e) {
                $e->display($e->getMessage());
            }
        }

        return self::$Output->write($output);
    }
	
}