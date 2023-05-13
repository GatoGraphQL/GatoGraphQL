<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\ModuleResolvers\ModuleResolverInterface;

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

    protected function getModuleDoesNotExistErrorMessage(string $module): string
    {
        return sprintf(
            \__('Oops, extension \'%s\' does not exist', 'gato-graphql'),
            $module
        );
    }

    protected function getModuleHasNoDocumentationErrorMessage(
        string $module,
        ModuleResolverInterface $moduleResolver,
    ): string {
        return sprintf(
            \__('Oops, extension \'%s\' has no documentation', 'gato-graphql'),
            $moduleResolver->getName($module)
        );
    }
}
