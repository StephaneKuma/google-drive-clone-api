<?php

namespace App\Helpers\Routes;

class RouteHelper
{
    public static function includeRouteFiles(string $directory): void
    {
        $directoryIterator = new \RecursiveDirectoryIterator($directory);

        /** @var \RecursiveDirectoryIterator | \RecursiveIteratorIterator $iterator */
        $iterator = new \RecursiveIteratorIterator($directoryIterator);

        while ($iterator->valid()) {
            if (!$iterator->isDot() && $iterator->isFile() && $iterator->isReadable() && $iterator->current()->getExtension() === 'php') {
                require $iterator->key();
            }

            $iterator->next();
        }
    }
}
