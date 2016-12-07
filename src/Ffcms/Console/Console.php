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
     * Console constructor. Create new entry point instance
     * @param array|null $services
     */
    public function __construct(array $services = null)
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
     * Build console instance factory
     * @param array|null $services
     * @return Console
     */
    public static function factory(array $services = null)
    {
        return new self($services);
    }
}