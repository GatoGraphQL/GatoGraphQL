<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\ComponentModel\Component\ApplicationEvents;
use PoP\ComponentModel\Config\ServiceConfiguration;
use PoP\ComponentModel\Container\CompilerPasses\AfterBootAttachExtensionCompilerPass;
use PoP\ComponentModel\Container\CompilerPasses\RegisterDirectiveResolverCompilerPass;
use PoP\ComponentModel\Container\CompilerPasses\BeforeBootAttachExtensionCompilerPass;
use PoP\ComponentModel\Container\CompilerPasses\InjectTypeResolverClassIntoTypeRegistryCompilerPass;
use PoP\ComponentModel\Container\CompilerPasses\RegisterFieldInterfaceResolverCompilerPass;
use PoP\ComponentModel\Environment;
use PoP\ComponentModel\Facades\AttachableExtensions\AttachExtensionServiceFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Root\Component\AbstractComponent;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public static function getDependedComponentClasses(): array
    {
        return [
            \PoP\Definitions\Component::class,
            \PoP\FieldQuery\Component::class,
        ];
    }

    public static function getDependedMigrationPlugins(): array
    {
        $packageName = basename(dirname(__DIR__));
        $folder = dirname(__DIR__, 2);
        return [
            $folder . '/migrate-' . $packageName . '/initialize.php',
        ];
    }

    /**
     * Initialize services
     *
     * @param array<string, mixed> $configuration
     * @param string[] $skipSchemaComponentClasses
     */
    protected static function doInitialize(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        parent::doInitialize($configuration, $skipSchema, $skipSchemaComponentClasses);
        ComponentConfiguration::setConfiguration($configuration);
        self::initYAMLServices(dirname(__DIR__));
        self::maybeInitYAMLSchemaServices(dirname(__DIR__), $skipSchema);
        ServiceConfiguration::initialize();
    }

    /**
     * Boot component
     *
     * @return void
     */
    public static function beforeBoot(): void
    {
        parent::beforeBoot();

        // Initialize the Component Configuration
        ComponentConfiguration::init();

        // Attach class extensions
        AttachExtensionServiceFacade::getInstance()->attachExtensions(ApplicationEvents::BEFORE_BOOT);
    }

    /**
     * Boot component
     *
     * @return void
     */
    public static function afterBoot(): void
    {
        parent::afterBoot();

        // Attach class extensions
        AttachExtensionServiceFacade::getInstance()->attachExtensions(ApplicationEvents::AFTER_BOOT);
    }

    /**
     * Define runtime constants
     */
    protected static function defineRuntimeConstants(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        // This Constant is needed to be able to retrieve the timestamp and replace it for nothing when generating the ETag,
        // so that this random value does not modify the hash of the overall html output
        define('POP_CONSTANT_UNIQUE_ID', GeneralUtils::generateRandomString());
        define('POP_CONSTANT_RAND', rand());
        define('POP_CONSTANT_TIME', time());

        // This value will be used in the response. If compact, make sure each JS Key is unique
        define('POP_RESPONSE_PROP_SUBMODULES', Environment::compactResponseJsonKeys() ? 'ms' : 'submodules');
    }

    /**
     * Get all the compiler pass classes required to register on the container
     *
     * @return string[]
     */
    public static function getContainerCompilerPassClasses(): array
    {
        return [
            InjectTypeResolverClassIntoTypeRegistryCompilerPass::class,
            RegisterDirectiveResolverCompilerPass::class,
            BeforeBootAttachExtensionCompilerPass::class,
            AfterBootAttachExtensionCompilerPass::class,
            RegisterFieldInterfaceResolverCompilerPass::class,
        ];
    }
}
