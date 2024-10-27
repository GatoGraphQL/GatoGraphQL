<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

class ModuleDocumentationMenuPage extends AbstractModuleDocsMenuPage
{
    private ?ModulesMenuPage $modulesMenuPage = null;

    final protected function getModulesMenuPage(): ModulesMenuPage
    {
        if ($this->modulesMenuPage === null) {
            /** @var ModulesMenuPage */
            $modulesMenuPage = $this->instanceManager->getInstance(ModulesMenuPage::class);
            $this->modulesMenuPage = $modulesMenuPage;
        }
        return $this->modulesMenuPage;
    }

    public function getMenuPageSlug(): string
    {
        return $this->getModulesMenuPage()->getMenuPageSlug();
    }

    public function getMenuPageTitle(): string
    {
        return $this->getModulesMenuPage()->getMenuPageTitle();
    }

    public function isServiceEnabled(): bool
    {
        return $this->getModulesMenuPage()->isServiceEnabled();
    }
}
