<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

class ExtensionDocumentationMenuPage extends AbstractModuleDocsMenuPage
{
    private ?ExtensionsMenuPage $extensionsMenuPage = null;

    final public function setExtensionsMenuPage(ExtensionsMenuPage $extensionsMenuPage): void
    {
        $this->extensionsMenuPage = $extensionsMenuPage;
    }
    final protected function getExtensionsMenuPage(): ExtensionsMenuPage
    {
        /** @var ExtensionsMenuPage */
        return $this->extensionsMenuPage ??= $this->instanceManager->getInstance(ExtensionsMenuPage::class);
    }

    public function getMenuPageSlug(): string
    {
        return $this->getExtensionsMenuPage()->getMenuPageSlug();
    }
}
