<?php

declare(strict_types=1);

namespace PoPCMSSchema\Media;

use PoP\Root\Module\AbstractModule;
use PoPCMSSchema\Users\Module as UsersModule;

class Module extends AbstractModule
{
    protected function requiresSatisfyingModule(): bool
    {
        return true;
    }

    /**
     * @return string[]
     */
    public function getDependedModuleClasses(): array
    {
        return [
            \PoP\Engine\Module::class,
            \PoPSchema\SchemaCommons\Module::class,
        ];
    }

    public function getDependedConditionalModuleClasses(): array
    {
        return [
            \PoPCMSSchema\Users\Module::class,
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
        $this->initSchemaServices(dirname(__DIR__), $skipSchema);

        if (class_exists(UsersModule::class)) {
            $this->initSchemaServices(
                dirname(__DIR__),
                $skipSchema || in_array(UsersModule::class, $skipSchemaModuleClasses),
                '/ConditionalOnModule/Users'
            );
        }
    }
}
