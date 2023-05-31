<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\ContentProcessors\NoDocsFolderPluginMarkdownContentRetrieverTrait;
use GatoGraphQL\GatoGraphQL\Registries\ModuleRegistryInterface;

class ExtensionDocsMenuPage extends AbstractVerticalTabDocsMenuPage
{
    use NoDocsFolderPluginMarkdownContentRetrieverTrait;

    private ?ModuleRegistryInterface $moduleRegistry = null;

    final public function setModuleRegistry(ModuleRegistryInterface $moduleRegistry): void
    {
        $this->moduleRegistry = $moduleRegistry;
    }
    final protected function getModuleRegistry(): ModuleRegistryInterface
    {
        /** @var ModuleRegistryInterface */
        return $this->moduleRegistry ??= $this->instanceManager->getInstance(ModuleRegistryInterface::class);
    }

    public function getMenuPageSlug(): string
    {
        return 'extensiondocs';
    }

    protected function getPageTitle(): string
    {
        return \__('Gato GraphQL - Entries: Use Cases, Best Practices, and Useful Queries', 'gato-graphql');
    }

    protected function getContentID(): string
    {
        return 'gato-graphql-extension-docs';
    }

    protected function getEntryRelativePathDir(): string
    {
        return 'extensions';
    }

    /**
     * @return array<array{0:string,1:string,2?:string[]}>
     */
    protected function getEntries(): array
    {
        return [
            [
                'access-control/docs/modules/access-control',
                \__('Access Control', 'gato-graphql'),
            ],
            [
                'access-control-visitor-ip/docs/modules/access-control-visitor-ip',
                \__('Access Control: Visitor IP', 'gato-graphql'),
            ],
        ];
    }
}
