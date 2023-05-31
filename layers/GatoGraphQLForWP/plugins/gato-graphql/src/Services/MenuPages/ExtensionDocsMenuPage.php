<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\ContentProcessors\NoDocsExtensionsFolderPluginMarkdownContentRetrieverTrait;

class ExtensionDocsMenuPage extends AbstractVerticalTabDocsMenuPage
{
    use NoDocsExtensionsFolderPluginMarkdownContentRetrieverTrait;

    public function getMenuPageSlug(): string
    {
        return 'extensiondocs';
    }

    protected function getPageTitle(): string
    {
        return \__('Gato GraphQL - Extension Docs', 'gato-graphql');
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
        $extensions = [
            'access-control' => \__('Access Control', 'gato-graphql'),
            'access-control-visitor-ip' => \__('Access Control: Visitor IP', 'gato-graphql'),
        ];
        $entries = [];
        foreach ($extensions as $extension => $extensionName) {
            $entries[] = [
                sprintf(
                    '%1$s/docs/modules/%1$s',
                    $extension
                ),
                $extensionName,
            ];
        }
        return $entries;
    }
}
