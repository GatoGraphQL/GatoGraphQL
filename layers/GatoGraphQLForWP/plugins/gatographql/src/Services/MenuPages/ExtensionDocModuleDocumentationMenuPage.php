<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

class ExtensionDocModuleDocumentationMenuPage extends AbstractExtensionModuleDocumentationMenuPage
{
    private ?ExtensionDocsMenuPage $extensionDocsMenuPage = null;

    final protected function getExtensionDocsMenuPage(): ExtensionDocsMenuPage
    {
        if ($this->extensionDocsMenuPage === null) {
            /** @var ExtensionDocsMenuPage */
            $extensionDocsMenuPage = $this->instanceManager->getInstance(ExtensionDocsMenuPage::class);
            $this->extensionDocsMenuPage = $extensionDocsMenuPage;
        }
        return $this->extensionDocsMenuPage;
    }

    public function getMenuPageSlug(): string
    {
        return $this->getExtensionDocsMenuPage()->getMenuPageSlug();
    }

    public function getMenuPageTitle(): string
    {
        return $this->getExtensionDocsMenuPage()->getMenuPageTitle();
    }

    public function isServiceEnabled(): bool
    {
        return $this->getExtensionDocsMenuPage()->isServiceEnabled();
    }
}
