<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentsWP;

use PoPCMSSchema\Users\Module as UsersModule;
use PoP\Root\App;
use PoP\Root\Module\AbstractModule;
use PoP\Root\Module\ModuleInterface;

class Module extends AbstractModule
{
    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getSatisfiedModuleClasses(): array
    {
        return [
            \PoPCMSSchema\Comments\Module::class,
        ];
    }

    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPCMSSchema\Comments\Module::class,
            \PoPCMSSchema\CustomPostsWP\Module::class,
        ];
    }

    /**
     * Initialize services
     *
     * @param array<class-string<ModuleInterface>> $skipSchemaModuleClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema,
        array $skipSchemaModuleClasses,
    ): void {
        $this->initServices(dirname(__DIR__));

        if (class_exists(UsersModule::class) && App::getModule(UsersModule::class)->isEnabled()) {
            $this->initServices(
                dirname(__DIR__),
                '/ConditionalOnModule/Users'
            );
            $this->initSchemaServices(
                dirname(__DIR__),
                $skipSchema || in_array(UsersModule::class, $skipSchemaModuleClasses),
                '/ConditionalOnModule/Users'
            );
        }
    }
}
