<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Constants\RequestParams;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\ModuleResolverInterface;

abstract class AbstractExtensionModuleDocumentationMenuPage extends AbstractModuleDocsMenuPage
{
    /**
     * If opening a Recipe doc in the iframe, do not use tabpanels
     */
    protected function useTabpanelForContent(): bool
    {
        if (
            $this->getMenuPageHelper()->isDocumentationScreen()
            && str_contains(App::query(RequestParams::DOC, ''), '../docs/tutorial/')
        ) {
            return false;
        }

        return parent::useTabpanelForContent();
    }

    protected function getModuleDoesNotExistErrorMessage(string $module): string
    {
        return sprintf(
            \__('Oops, extension \'%s\' does not exist', 'gatographql'),
            $module
        );
    }

    protected function getModuleHasNoDocumentationErrorMessage(
        string $module,
        ModuleResolverInterface $moduleResolver,
    ): string {
        return sprintf(
            \__('Oops, extension \'%s\' has no documentation', 'gatographql'),
            $moduleResolver->getName($module)
        );
    }

    /**
     * Any one document under bundle-extensions. It doesn't matter
     * which one, as all links will start with "../" anyway
     */
    protected function getRelativePathDir(): string
    {
        $anyDocumentFolder = 'access-control';
        return 'extensions/' . $anyDocumentFolder . '/docs/modules/' . $anyDocumentFolder;
    }

    /**
     * Get the URL where to look for the documentation.
     */
    protected function getDocsFolder(): string
    {
        return '';
    }
}
