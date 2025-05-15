<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use PoP\ComponentModel\App;

class LogsMenuPage extends AbstractPluginMenuPage
{
    private ?ModuleRegistryInterface $moduleRegistry = null;

    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        if ($this->moduleRegistry === null) {
            /** @var ModuleRegistryInterface */
            $moduleRegistry = $this->instanceManager->getInstance(ModuleRegistryInterface::class);
            $this->moduleRegistry = $moduleRegistry;
        }
        return $this->moduleRegistry;
    }

    public function isServiceEnabled(): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->enableLogs()) {
            return false;
        }
        return parent::isServiceEnabled();
    }

    public function print(): void
    {
        ?>
        <!-- @todo -->
        <?php
    }

    public function getMenuPageSlug(): string
    {
        return 'logs';
    }

    public function getMenuPageTitle(): string
    {
        return __('Logs', 'gatographql');
    }
}
