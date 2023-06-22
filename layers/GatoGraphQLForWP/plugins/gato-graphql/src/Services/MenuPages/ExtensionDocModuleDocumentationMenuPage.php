<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

class ExtensionDocModuleDocumentationMenuPage extends AbstractExtensionModuleDocumentationMenuPage
{
    private ?ExtensionDocsMenuPage $extensionDocsMenuPage = null;

    final public function setExtensionDocsMenuPage(ExtensionDocsMenuPage $extensionDocsMenuPage): void
    {
        $this->extensionDocsMenuPage = $extensionDocsMenuPage;
    }
    final protected function getExtensionDocsMenuPage(): ExtensionDocsMenuPage
    {
        /** @var ExtensionDocsMenuPage */
        return $this->extensionDocsMenuPage ??= $this->instanceManager->getInstance(ExtensionDocsMenuPage::class);
    }

    public function getMenuPageSlug(): string
    {
        return $this->getExtensionDocsMenuPage()->getMenuPageSlug();
    }
}
