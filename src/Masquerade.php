<?php

namespace Aztech\Carnival;

/**
 * Generate static classes from an object instance, to generate static facades.
 *
 * @author thibaud
 */
class Masquerade
{

    private static $masquerades = array();

    private static $classTemplate = <<<'EOT'
    class %1$s {
        public static function __callstatic($name, $args) {
            return \Aztech\Carnival\Masquerade::invoke('%2$s', $name, $args);
        }
    };
EOT;

    private static $nsTemplate = <<<'EOT'
namespace %s {
%s
}
EOT;

    final public static function bind($masqueradeName, $instance)
    {
        if (! is_object($instance)) {
            throw new \InvalidArgumentException('$instance is not an object.');
        }

        if (class_exists($masqueradeName)) {
            throw new \InvalidArgumentException('Class name already exists.');
        }

        if (substr($masqueradeName, 0, 1) == '\\') {
            $masqueradeName = substr($masqueradeName, 1);
        }

        self::$masquerades[$masqueradeName] = $instance;
    }

    /**
     *
     * @param string $className
     * @return boolean
     *
     * @SuppressWarnings(PHPMD.EvalExpression)
     */
    final public static function buildClass($className)
    {
        if (! array_key_exists($className, self::$masquerades)) {
            return false;
        }

        $code = sprintf(self::$classTemplate, $className, $className);

        if (strpos($className, '\\') !== false) {
            list ($class, $namespace) = explode('\\', strrev($className), 2);
            $namespace = strrev($namespace);
            $class = strrev($class);

            $code = sprintf(self::$nsTemplate, $namespace, sprintf(self::$classTemplate, $class, $className));
        }

        return eval($code);
    }

    final public static function invoke($class, $method, $args)
    {
        return call_user_func_array(array(
            self::$masquerades[$class],
            $method
        ), $args);
    }

    final public static function register()
    {
        spl_autoload_register('Aztech\Carnival\Masquerade::buildClass', false, true);
    }
}
