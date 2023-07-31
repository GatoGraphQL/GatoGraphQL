<?php

declare(strict_types=1);

namespace PoP\Root\Container\Dumper;

use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\DependencyInjection\Exception\EnvParameterException;

/**
 * Starting from Symfony Dependency Injection v2.6,
 * PhpDumper generates code that contains `??=`,
 * so it's compatible with PHP 7.4+.
 *
 * For instance, this function is generated in v2.6:
 *
 * ```
 *   protected function getClientFunctionalityModuleResolverService()
 *   {
 *       $this->services['GatoGraphQL\\GatoGraphQL\\ModuleResolvers\\ClientFunctionalityModuleResolver'] = $instance = new \GatoGraphQL\GatoGraphQL\ModuleResolvers\ClientFunctionalityModuleResolver();
 *
 *       $instance->setInstanceManager(($this->services['PoP\\Root\\Instances\\InstanceManagerInterface'] ??= new \PoP\Root\Instances\SystemInstanceManager()));
 *
 *       return $instance;
 *   }
 * ```
 *
 * The same function was generated like this in v2.5:
 *
 * ```
 *   protected function getClientFunctionalityModuleResolverService()
 *   {
 *       $this->services['GatoGraphQL\\GatoGraphQL\\ModuleResolvers\\ClientFunctionalityModuleResolver'] = $instance = new \GatoGraphQL\GatoGraphQL\ModuleResolvers\ClientFunctionalityModuleResolver();
 *
 *       $instance->setInstanceManager(($this->services['PoP\\Root\\Instances\\InstanceManagerInterface'] ?? ($this->services['PoP\\Root\\Instances\\InstanceManagerInterface'] = new \PoP\Root\Instances\SystemInstanceManager())));
 *
 *       return $instance;
 *   }
 * ```
 *
 * This class extends PhpDumper to replace the `??=` code,
 * making it compatible with PHP 7.2+ once again.
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
     * @param array<string,mixed> $options
     * @return string|string[] A PHP class representing the service container or an array of PHP files if the "as_files" option is set
     *
     * @throws EnvParameterException When an env var exists but has not been dumped
     */
    public function dump(array $options = []): string|array
    {
        $dump = parent::dump($options);

        /**
         * Regex: match anything except whitespaces => it'll capture the service name or class.
         *
         * This regex will match code like this one:
         *
         *   ```
         *   $instance->setInstanceManager(($container->services['PoP\\Root\\Instances\\InstanceManagerInterface'] ??= new \PoP\Root\Instances\SystemInstanceManager()));
         *   ```
         *
         * @var string|string[]
         */
        return preg_replace(
            '/\$([a-zA-Z0-9_]+)->services\[\'([^\s]*)\'\] \?\?= new ([^\s]*)\(\)/',
            '\$$1->services[\'$2\'] ?? (\$$1->services[\'$2\'] = new $3())',
            $dump
        );
    }
}
