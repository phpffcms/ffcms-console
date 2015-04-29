<?php 

namespace Ffcms\Console;

use Core\Exception\NativeException;
use \Core\Property;
use \Console\Transfer\Input;
use \Console\Transfer\Output;


class App
{
    /** @var \Core\Property */
	public static $Property;
    /** @var \Console\Transfer\Input */
    public static $Input;
    /** @var \Console\Transfer\Output */
    public static $Output;

    /**
     * Build console entry point
     */
	public static function build()
	{
		self::$Property = new Property();
        self::$Input = new Input();
        self::$Output = new Output();
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
                $controller_path = '/controller/' . workground . '/' . $controller . '.php';
                if(file_exists(root . $controller_path) && is_readable(root . $controller_path)) {
                    include_once(root . $controller_path);
                    $cname = 'Controller\\' . workground . '\\' . $controller;
                    if(class_exists($cname)) {
                        $load = new $cname;
                        if(method_exists($cname, $action)) {
                            if($id !== null) {
                                $output = @$load->$action($id);
                            } else {
                                $output = @$load->$action();
                            }
                        } else {
                            throw new \Exception('Method ' . $action . '() not founded in ' . $cname . ' in file {root}' . $controller_path);
                        }
                        unset($load);
                    } else {
                        throw new \Exception('Namespace\\Class - ' . $cname . ' not founded in {root}' . $controller_path);
                    }
                } else {
                    throw new \Exception('Controller not founded: {root}' . $controller_path);
                }
            } catch(\Exception $e) {
                new NativeException($e);
            }
        }

        return $output;
    }
	
}