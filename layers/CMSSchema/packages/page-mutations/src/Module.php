<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations;

use PoP\Root\Module\ModuleInterface;
use PoP\Root\App;
use PoPAPI\API\Module as APIModule;
use PoP\Root\Module\AbstractModule;
use PoPCMSSchema\CommentMutations\Module as CommentMutationsModule;
use PoPCMSSchema\Users\Module as UsersModule;

class Module extends AbstractModule
{
    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPCMSSchema\CustomPostMutations\Module::class,
            \PoPCMSSchema\Pages\Module::class,
        ];
    }

    /**
     * @return array<class-string<ModuleInterface>>
     */
    public function getDependedConditionalModuleClasses(): array
    {
        return [
            \PoPAPI\API\Module::class,
            \PoPCMSSchema\CommentMutations\Module::class,
            \PoPCMSSchema\Users\Module::class,
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
        $this->initSchemaServices(dirname(__DIR__), $skipSchema);
        if (class_exists(APIModule::class) && App::getModule(APIModule::class)->isEnabled()) {
            $this->initServices(dirname(__DIR__), '/ConditionalOnModule/API');
        }
        if (class_exists(UsersModule::class) && App::getModule(UsersModule::class)->isEnabled()) {
            $this->initSchemaServices(
                dirname(__DIR__),
                $skipSchema || in_array(UsersModule::class, $skipSchemaModuleClasses),
                '/ConditionalOnModule/Users'
            );
        }
        if (class_exists(CommentMutationsModule::class) && App::getModule(CommentMutationsModule::class)->isEnabled()) {
            $this->initSchemaServices(
                dirname(__DIR__),
                $skipSchema || in_array(CommentMutationsModule::class, $skipSchemaModuleClasses),
                '/ConditionalOnModule/CommentMutations'
            );
        }
    }
}
