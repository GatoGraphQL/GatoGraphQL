<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutationsWP;

use PoP\Root\Module\AbstractModule;

class Module extends AbstractModule
{
    /**
     * @return string[]
     */
    public function getSatisfiedModuleClasses(): array
    {
        return [
            \PoPCMSSchema\CustomPostMutations\Module::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoPCMSSchema\CustomPostMutations\Module::class,
            \PoPCMSSchema\CustomPostsWP\Module::class,
            \PoPCMSSchema\UserStateWP\Module::class,
            \PoPCMSSchema\UserRolesWP\Module::class,
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
    }
}
