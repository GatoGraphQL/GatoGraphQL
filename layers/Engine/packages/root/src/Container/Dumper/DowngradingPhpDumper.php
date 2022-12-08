<?php

declare(strict_types=1);

namespace PoP\Root\Container\Dumper;

use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Exception\EnvParameterException;

/**
 * Starting from Symfony v2.6, PhpDumper generates code
 * that contains `??=`, so it's compatible with PHP 7.4+.
 *
 * Replace the code to make it compatible with PHP 7.1+
 */
class DowngradingPhpDumper extends PhpDumper
{
    /**
     * Dumps the service container as a PHP class.
     *
     * Available options:
     *
     *  * class:      The class name
     *  * base_class: The base class name
     *  * namespace:  The class namespace
     *  * as_files:   To split the container in several files
     *
     * @return string|array A PHP class representing the service container or an array of PHP files if the "as_files" option is set
     *
     * @throws EnvParameterException When an env var exists but has not been dumped
     */
    public function dump(array $options = []): string|array
    {
        $dump = parent::dump($options);

        return $dump;
    }
}
