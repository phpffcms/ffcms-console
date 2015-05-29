<?php 

namespace Ffcms\Console;

use Ffcms\Core\Exception\NativeException;
use Ffcms\Core\Property;
use Ffcms\Console\Transfer\Input;
use Ffcms\Console\Transfer\Output;
use \Illuminate\Database\Capsule\Manager as Capsule;

class App
{
    /** @var \Ffcms\Core\Property */
	public static $Property;
    /** @var \Ffcms\Console\Transfer\Input */
    public static $Input;
    /** @var \Ffcms\Console\Transfer\Output */
    public static $Output;
    /** @var \Illuminate\Database\Capsule\Manager */
    public static $Database;

    /**
     * Build console entry point
     */
	public static function build()
	{
		self::$Property = new Property();
        self::$Input = new Input();
        self::$Output = new Output();

        // establish database link
        if (is_array(self::$Property->get('database'))) {
            self::$Database = new Capsule;
            self::$Database->addConnection(self::$Property->get('database'));

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
    public static function display()
    {
        global $argv;
        $output = null;
        if ($argv[1] == null) {
            $output = 'Console command is unknown! Type "php console.php main/help" to get help guide';
        } else {
            $controller_action = $argv[1];
            $arrInput = explode('/', $controller_action);
            $controller = ucfirst(strtolower($arrInput[0]));
            $action = ucfirst(strtolower($arrInput[1]));
            if($action == null) {
                $action = 'Index';
            }
            $action = 'action' . $action;
            $id = $argv[2];

            try {
                $controller_path = '/Apps/Controller/' . env_name . '/' . $controller . '.php';
                if(file_exists(root . $controller_path) && is_readable(root . $controller_path)) {
                    include_once(root . $controller_path);
                    $cname = 'Apps\\Controller\\' . env_name . '\\' . $controller;
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

        return $output;
    }
	
}