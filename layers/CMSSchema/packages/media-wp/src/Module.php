<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaWP;

use PoPCMSSchema\Users\Module as UsersModule;
use PoP\Root\App;
use PoP\Root\Exception\ComponentNotExistsException;
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
            \PoPCMSSchema\Media\Module::class,
        ];
    }

    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPCMSSchema\Media\Module::class,
            \PoPCMSSchema\CustomPostsWP\Module::class,
        ];
    }

    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedConditionalModuleClasses(): array
    {
        return [
            \PoPCMSSchema\UsersWP\Module::class,
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

        try {
            if (class_exists(UsersModule::class) && App::getModule(UsersModule::class)->isEnabled()) {
                $this->initSchemaServices(
                    dirname(__DIR__),
                    $skipSchema || in_array(UsersModule::class, $skipSchemaModuleClasses),
                    '/ConditionalOnModule/Users'
                );
            }
        } catch (ComponentNotExistsException) {
        }
    }
}
