<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Services\Blocks\MainPluginBlockTrait;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\EndpointBlockCategory;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractQueryExecutionOptionsBlock;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\ClientFunctionalityModuleResolver;

/**
 * Endpoint Options block
 */
class EndpointOptionsBlock extends AbstractQueryExecutionOptionsBlock implements EndpointEditorBlockServiceTagInterface
{
    use MainPluginBlockTrait;

    public const ATTRIBUTE_NAME_IS_GRAPHIQL_ENABLED = 'isGraphiQLEnabled';
    public const ATTRIBUTE_NAME_IS_VOYAGER_ENABLED = 'isVoyagerEnabled';

    protected function getBlockName(): string
    {
        return 'endpoint-options';
    }

    public function getBlockPriority(): int
    {
        return 160;
    }

    protected function getBlockCategoryClass(): ?string
    {
        return EndpointBlockCategory::class;
    }

    /**
     * @param array<string, mixed> $attributes
     */
    protected function getBlockContent(array $attributes, string $content): string
    {
        $blockContent = parent::getBlockContent($attributes, $content);

        $blockContentPlaceholder = '<p><strong>%s</strong></p><p>%s</p>';
        if ($this->moduleRegistry->isModuleEnabled(ClientFunctionalityModuleResolver::GRAPHIQL_FOR_CUSTOM_ENDPOINTS)) {
            $blockContent .= sprintf(
                $blockContentPlaceholder,
                \__('Expose GraphiQL client?', 'graphql-api'),
                $this->getBooleanLabel($attributes[self::ATTRIBUTE_NAME_IS_GRAPHIQL_ENABLED] ?? true)
            );
        }
        if ($this->moduleRegistry->isModuleEnabled(ClientFunctionalityModuleResolver::INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS)) {
            $blockContent .= sprintf(
                $blockContentPlaceholder,
                \__('Expose the Interactive Schema client?', 'graphql-api'),
                $this->getBooleanLabel($attributes[self::ATTRIBUTE_NAME_IS_VOYAGER_ENABLED] ?? true)
            );
        }

        return $blockContent;
    }

    /**
     * Pass localized data to the block
     *
     * @return array<string, mixed>
     */
    protected function getLocalizedData(): array
    {
        return array_merge(
            parent::getLocalizedData(),
            [
                'isGraphiQLEnabled' => $this->moduleRegistry->isModuleEnabled(ClientFunctionalityModuleResolver::GRAPHIQL_FOR_CUSTOM_ENDPOINTS),
                'isVoyagerEnabled' => $this->moduleRegistry->isModuleEnabled(ClientFunctionalityModuleResolver::INTERACTIVE_SCHEMA_FOR_CUSTOM_ENDPOINTS),
            ]
        );
    }
}
