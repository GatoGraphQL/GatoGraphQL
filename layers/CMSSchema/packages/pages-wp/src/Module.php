<?php

declare(strict_types=1);

namespace PoPCMSSchema\PagesWP;

use PoP\Root\App;
use PoP\Root\Module\AbstractModule;
use PoPCMSSchema\Pages\ModuleConfiguration as PagesModuleConfiguration;
use PoPCMSSchema\Pages\Module as PagesModule;

class Module extends AbstractModule
{
    /**
     * @return string[]
     */
    public function getSatisfiedModuleClasses(): array
    {
        return [
            \PoPCMSSchema\Pages\Module::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPCMSSchema\Pages\Module::class,
            \PoPCMSSchema\CustomPostsWP\Module::class,
        ];
    }

    /**
     * Initialize services
     *
     * @param string[] $skipSchemaModuleClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema,
        array $skipSchemaModuleClasses,
    ): void {
        $this->initServices(dirname(__DIR__));
        /** @var PagesModuleConfiguration */
        $moduleConfiguration = App::getModule(PagesModule::class)->getConfiguration();
        if ($moduleConfiguration->addPageTypeToCustomPostUnionTypes()) {
            $this->initSchemaServices(dirname(__DIR__), $skipSchema, '/ConditionalOnContext/AddPageTypeToCustomPostUnionTypes/Overrides');
        }
    }
}
