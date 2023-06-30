<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\Constants\RequestParams;
use GatoGraphQL\GatoGraphQL\ContentProcessors\PluginMarkdownContentRetrieverTrait;
use GatoGraphQL\GatoGraphQL\Exception\ModuleNotExistsException;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\ModuleResolverInterface;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;
use PoP\Root\App;

abstract class AbstractModuleDocsMenuPage extends AbstractDocsMenuPage
{
    use PluginMarkdownContentRetrieverTrait;

    private ?ModuleRegistryInterface $moduleRegistry = null;

    final public function setModuleRegistry(ModuleRegistryInterface $moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        if ($this->moduleRegistry === null) {
            /** @var ModuleRegistryInterface */
            $moduleRegistry = $this->instanceManager->getInstance(ModuleRegistryInterface::class);
            $this->moduleRegistry = $moduleRegistry;
        }
        return $this->moduleRegistry;
    }

    /**
     * Validate the param also
     */
    protected function isCurrentScreen(): bool
    {
        return $this->getMenuPageHelper()->isDocumentationScreen() && parent::isCurrentScreen();
    }

    protected function openInModalWindow(): bool
    {
        return true;
    }

    protected function useTabpanelForContent(): bool
    {
        return true;
    }

    protected function getContentToPrint(): string
    {
        // This is crazy: passing ?module=Foo\Bar\module,
        // and then doing $_GET['module'], returns "Foo\\Bar\\module"
        // So parse the URL to extract the "module" param
        /** @var array<string,mixed> */
        $result = [];
        parse_str(App::server('REQUEST_URI'), $result);
        /** @var string */
        $requestedModule = $result[RequestParams::MODULE] ?? '';
        if ($requestedModule === '') {
            return sprintf(
                '<p>%s</p>',
                $this->getModuleCannotBeEmpty()
            );
        }
        $module = urldecode($requestedModule);
        try {
            $moduleResolver = $this->getModuleRegistry()->getModuleResolver($module);
        } catch (ModuleNotExistsException) {
            return sprintf(
                '<p>%s</p>',
                $this->getModuleDoesNotExistErrorMessage($module)
            );
        }
        $hasDocumentation = $moduleResolver->hasDocumentation($module);
        $documentation = '';
        if ($hasDocumentation) {
            $documentation = $moduleResolver->getDocumentation($module);
        }
        if (!$hasDocumentation || $documentation === null) {
            return sprintf(
                '<p>%s</p>',
                $this->getModuleHasNoDocumentationErrorMessage(
                    $module,
                    $moduleResolver,
                )
            );
        }
        return $documentation;
    }

    protected function getModuleCannotBeEmpty(): string
    {
        return sprintf(
            \__('URL param \'%s\' cannot be empty', 'gato-graphql'),
            RequestParams::MODULE
        );
    }

    protected function getModuleDoesNotExistErrorMessage(string $module): string
    {
        return sprintf(
            \__('Oops, module \'%s\' does not exist', 'gato-graphql'),
            $module
        );
    }

    protected function getModuleHasNoDocumentationErrorMessage(
        string $module,
        ModuleResolverInterface $moduleResolver,
    ): string {
        return sprintf(
            \__('Oops, module \'%s\' has no documentation', 'gato-graphql'),
            $moduleResolver->getName($module)
        );
    }
}
