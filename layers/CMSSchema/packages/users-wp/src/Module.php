<?php

declare(strict_types=1);

namespace PoPCMSSchema\UsersWP;

use PoP\Root\Module\ModuleInterface;
use PoP\Root\Module\AbstractModule;
use PoPCMSSchema\CustomPosts\Module as CustomPostsModule;

class Module extends AbstractModule
{
    /**
     * @return array<class-string<\PoPCMSSchema\Users\Module>>
     */
    public function getSatisfiedModuleClasses(): array
    {
        return [
            \PoPCMSSchema\Users\Module::class,
        ];
    }

    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPCMSSchema\Users\Module::class,
            \PoPCMSSchema\QueriedObjectWP\Module::class,
        ];
    }

    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedConditionalModuleClasses(): array
    {
        return [
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
        if (class_exists(CustomPostsModule::class)) {
            $this->initServices(dirname(__DIR__), '/ConditionalOnModule/CustomPosts');
        }
    }
}
