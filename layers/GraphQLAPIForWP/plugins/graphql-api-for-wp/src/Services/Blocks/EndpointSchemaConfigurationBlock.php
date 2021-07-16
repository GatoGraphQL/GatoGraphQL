<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use GraphQLAPI\GraphQLAPI\Services\Helpers\CPTUtils;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockRenderingHelpers;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\EndpointFunctionalityModuleResolver;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\EndpointBlockCategory;

/**
 * SchemaConfiguration block
 */
class EndpointSchemaConfigurationBlock extends AbstractBlock implements PersistedQueryEndpointEditorBlockServiceTagInterface, EndpointEditorBlockServiceTagInterface
{
    use MainPluginBlockTrait;

    public const ATTRIBUTE_NAME_SCHEMA_CONFIGURATION = 'schemaConfiguration';
    /**
     * These consts must be integer!
     */
    public const ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_DEFAULT = 0;
    public const ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_NONE = -1;
    public const ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_INHERIT = -2;

    protected function getBlockName(): string
    {
        return 'schema-configuration';
    }

    public function getBlockPriority(): int
    {
        return 180;
    }

    protected function getBlockCategoryClass(): ?string
    {
        return EndpointBlockCategory::class;
    }

    protected function isDynamicBlock(): bool
    {
        return true;
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
                'isAPIHierarchyEnabled' => $this->moduleRegistry->isModuleEnabled(EndpointFunctionalityModuleResolver::API_HIERARCHY),
            ]
        );
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function renderBlock(array $attributes, string $content): string
    {
        /**
         * Print the list of all the contained Access Control blocks
         */
        $blockContentPlaceholder = <<<EOF
        <div class="%s">
            <h3 class="%s">%s</strong></h3>
            %s
        </div>
EOF;
        $schemaConfigurationContent = '';
        $schemaConfigurationID = $attributes[self::ATTRIBUTE_NAME_SCHEMA_CONFIGURATION] ?? null;
        if ($schemaConfigurationID == self::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_DEFAULT) {
            $schemaConfigurationContent = \__('Default', 'graphql-api');
        } elseif ($schemaConfigurationID == self::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_NONE) {
            $schemaConfigurationContent = \__('None', 'graphql-api');
        } elseif ($schemaConfigurationID == self::ATTRIBUTE_VALUE_SCHEMA_CONFIGURATION_INHERIT) {
            $schemaConfigurationContent = \__('Inherit from parent', 'graphql-api');
        } elseif ($schemaConfigurationID > 0) {
            $schemaConfigurationObject = \get_post($schemaConfigurationID);
            if (!is_null($schemaConfigurationObject)) {
                /** @var BlockRenderingHelpers */
                $blockRenderingHelpers = $this->instanceManager->getInstance(BlockRenderingHelpers::class);
                /** @var CPTUtils */
                $cptUtils = $this->instanceManager->getInstance(CPTUtils::class);
                $schemaConfigurationDescription = $cptUtils->getCustomPostDescription($schemaConfigurationObject);
                $permalink = \get_permalink($schemaConfigurationObject->ID);
                $schemaConfigurationContent = ($permalink ?
                    \sprintf(
                        '<code><a href="%s">%s</a></code>',
                        $permalink,
                        $blockRenderingHelpers->getCustomPostTitle($schemaConfigurationObject)
                    ) :
                    \sprintf(
                        '<code>%s</code>',
                        $blockRenderingHelpers->getCustomPostTitle($schemaConfigurationObject)
                    )
                ) . ($schemaConfigurationDescription ?
                    '<br/><small>' . $schemaConfigurationDescription . '</small>'
                    : ''
                );
            }
        }
        $className = $this->getBlockClassName();
        return sprintf(
            $blockContentPlaceholder,
            $className,
            $className . '-front',
            \__('Schema Configuration', 'graphql-api'),
            $schemaConfigurationContent
        );
    }
}
