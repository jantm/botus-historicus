<?php

class Autoloader
{
    /**
     * Autoload a class based on the namespace.
     *
     * @param string $namespace
     */
    public static function autoload($namespace)
    {
        $namespace = explode('\\', $namespace);
        $file = array_pop($namespace) . '.php';

        if (empty($namespace)) {
            return;
        }

        require_once __DIR__ . '/../../' . strtolower(implode('/', $namespace)) . '/' . lcfirst($file);
    }
}

spl_autoload_register('Autoloader::autoload');
