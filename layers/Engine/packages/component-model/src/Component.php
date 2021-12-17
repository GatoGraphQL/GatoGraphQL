<?php

declare(strict_types=1);

namespace PoP\ComponentModel;

use PoP\ComponentModel\Facades\AttachableExtensions\AttachExtensionServiceFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Root\Component\AbstractComponent;
use PoP\Root\Component\ApplicationEvents;

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
            \PoP\GraphQLParser\Component::class,
            \PoP\BasicService\Component::class,
        ];
    }

    /**
     * Initialize services
     *
     * @param array<string, mixed> $configuration
     * @param string[] $skipSchemaComponentClasses
     */
    protected static function initializeContainerServices(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        ComponentConfiguration::setConfiguration($configuration);
        self::initServices(dirname(__DIR__));
        self::initServices(dirname(__DIR__), '/Overrides');
        self::initSchemaServices(dirname(__DIR__), $skipSchema);
    }

    /**
     * Initialize services for the system container
     */
    protected static function initializeSystemContainerServices(): void
    {
        self::initSystemServices(dirname(__DIR__));
    }

    public static function beforeBoot(): void
    {
        parent::beforeBoot();

        // Initialize the Component Configuration
        ComponentConfiguration::init();

        $attachExtensionService = AttachExtensionServiceFacade::getInstance();
        $attachExtensionService->attachExtensions(ApplicationEvents::BEFORE_BOOT);
    }

    public static function boot(): void
    {
        parent::boot();

        $attachExtensionService = AttachExtensionServiceFacade::getInstance();
        $attachExtensionService->attachExtensions(ApplicationEvents::BOOT);
    }

    public static function afterBoot(): void
    {
        parent::afterBoot();

        $attachExtensionService = AttachExtensionServiceFacade::getInstance();
        $attachExtensionService->attachExtensions(ApplicationEvents::AFTER_BOOT);
    }

    /**
     * Define runtime constants
     */
    protected static function defineRuntimeConstants(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        ComponentInfo::init([
            // This Constant is needed to be able to retrieve the timestamp and replace it for nothing when generating the ETag,
            // so that this random value does not modify the hash of the overall html output
            'unique-id' => GeneralUtils::generateRandomString(),
            'rand' => rand(),
            'time' => time(),
            // This value will be used in the response. If compact, make sure each JS Key is unique
            'response-prop-submodules' => Environment::compactResponseJsonKeys() ? 'ms' : 'submodules',
        ]);
    }
}
