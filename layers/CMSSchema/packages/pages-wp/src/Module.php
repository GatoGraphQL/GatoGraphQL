<?php

declare(strict_types=1);

namespace PoPCMSSchema\PagesWP;

use PoP\Root\App;
use PoP\Root\Module\AbstractModule;
use PoPCMSSchema\Pages\ModuleConfiguration as PagesComponentConfiguration;
use PoPCMSSchema\Pages\Module as PagesModule;

/**
 * Initialize component
 */
class Module extends AbstractModule
{
    /**
     * All component classes that this component satisfies
     *
     * @return string[]
     */
    public function getSatisfiedComponentClasses(): array
    {
        return [
            \PoPCMSSchema\Pages\Module::class,
        ];
    }

    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public function getDependedComponentClasses(): array
    {
        return [
            \PoPCMSSchema\Pages\Module::class,
            \PoPCMSSchema\CustomPostsWP\Module::class,
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
        /** @var PagesComponentConfiguration */
        $moduleConfiguration = App::getComponent(PagesModule::class)->getConfiguration();
        if ($moduleConfiguration->addPageTypeToCustomPostUnionTypes()) {
            $this->initSchemaServices(dirname(__DIR__), $skipSchema, '/ConditionalOnContext/AddPageTypeToCustomPostUnionTypes/Overrides');
        }
    }
}
