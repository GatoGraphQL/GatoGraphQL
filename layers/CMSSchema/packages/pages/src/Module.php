<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages;

use PoP\Root\App;
use PoPAPI\API\Module as APIModule;
use PoPAPI\RESTAPI\Module as RESTAPIComponent;
use PoP\Root\Module\AbstractModule;

/**
 * Initialize component
 */
class Module extends AbstractModule
{
    protected function requiresSatisfyingComponent(): bool
    {
        return true;
    }

    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public function getDependedComponentClasses(): array
    {
        return [
            \PoPCMSSchema\CustomPosts\Module::class,
        ];
    }

    /**
     * All conditional component classes that this component depends upon, to initialize them
     */
    public function getDependedConditionalComponentClasses(): array
    {
        return [
            \PoPAPI\API\Module::class,
            \PoPAPI\RESTAPI\Module::class,
        ];
    }

    /**
     * Initialize services
     *
     * @param string[] $skipSchemaComponentClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema,
        array $skipSchemaComponentClasses,
    ): void {
        $this->initServices(dirname(__DIR__));
        $this->initSchemaServices(dirname(__DIR__), $skipSchema);
        /** @var ModuleConfiguration */
        $componentConfiguration = $this->getConfiguration();
        if ($componentConfiguration->addPageTypeToCustomPostUnionTypes()) {
            $this->initSchemaServices(dirname(__DIR__), $skipSchema, '/ConditionalOnContext/AddPageTypeToCustomPostUnionTypes');
        }
        if (class_exists(APIModule::class) && App::getComponent(APIModule::class)->isEnabled()) {
            $this->initServices(dirname(__DIR__), '/ConditionalOnComponent/API');
        }
        if (class_exists(RESTAPIComponent::class) && App::getComponent(RESTAPIComponent::class)->isEnabled()) {
            $this->initServices(dirname(__DIR__), '/ConditionalOnComponent/RESTAPI');
        }
    }
}
