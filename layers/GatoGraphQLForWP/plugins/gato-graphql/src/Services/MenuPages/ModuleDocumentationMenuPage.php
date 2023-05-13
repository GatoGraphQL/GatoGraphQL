<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

class ModuleDocumentationMenuPage extends AbstractModuleDocsMenuPage
{
    private ?ModulesMenuPage $modulesMenuPage = null;

    final public function setModulesMenuPage(ModulesMenuPage $modulesMenuPage): void
    {
        $this->modulesMenuPage = $modulesMenuPage;
    }
    final protected function getModulesMenuPage(): ModulesMenuPage
    {
        /** @var ModulesMenuPage */
        return $this->modulesMenuPage ??= $this->instanceManager->getInstance(ModulesMenuPage::class);
    }

    public function getMenuPageSlug(): string
    {
        return $this->getModulesMenuPage()->getMenuPageSlug();
    }
}
