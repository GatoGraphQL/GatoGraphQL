<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use PoP\AccessControl\Schema\SchemaModes;
use PoP\AccessControl\ComponentConfiguration;
use GraphQLAPI\GraphQLAPI\Services\Blocks\AbstractControlBlock;
use GraphQLAPI\GraphQLAPI\Services\Blocks\GraphQLByPoPBlockTrait;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\AbstractBlockCategory;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\AccessControlBlockCategory;

/**
 * Access Control block
 */
class AccessControlBlock extends AbstractControlBlock
{
    use GraphQLByPoPBlockTrait;

    public const ATTRIBUTE_NAME_SCHEMA_MODE = 'schemaMode';

    protected function getBlockName(): string
    {
        return 'access-control';
    }

    protected function getBlockCategory(): ?AbstractBlockCategory
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var AccessControlBlockCategory
         */
        $blockCategory = $instanceManager->getInstance(AccessControlBlockCategory::class);
        return $blockCategory;
    }

    protected function registerEditorCSS(): bool
    {
        return true;
    }

    protected function registerCommonStyleCSS(): bool
    {
        return true;
    }

    protected function getBlockDataTitle(): string
    {
        return \__('Define access for:', 'graphql-api');
    }
    protected function getBlockContentTitle(): string
    {
        if (ComponentConfiguration::enableIndividualControlForPublicPrivateSchemaMode()) {
            return \__('Access Control Rules:', 'graphql-api');
        }
        return \__('Who can access:', 'graphql-api');
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
                'isIndividualControlForSchemaModeEnabled' => ComponentConfiguration::enableIndividualControlForPublicPrivateSchemaMode(),
            ]
        );
    }

    /**
     * Return the nested blocks' content
     *
     * @param array<string, mixed> $attributes
     */
    protected function getBlockContent(array $attributes, string $content): string
    {
        $maybeSchemaModeContent = '';
        if (ComponentConfiguration::enableIndividualControlForPublicPrivateSchemaMode()) {
            $blockContentPlaceholder = <<<EOT
                <p><strong>%s</strong> %s</p>
                <h4 class="%s">%s</h4>
EOT;
            $className = $this->getBlockClassName() . '-front';
            $schemaModeLabels = [
                SchemaModes::PUBLIC_SCHEMA_MODE => \__('Public', 'graphql-api'),
                SchemaModes::PRIVATE_SCHEMA_MODE => \__('Private', 'graphql-api'),
            ];
            $maybeSchemaModeContent = sprintf(
                $blockContentPlaceholder,
                \__('Public/Private Schema:', 'graphql-api'),
                isset($attributes[self::ATTRIBUTE_NAME_SCHEMA_MODE]) ?
                    $schemaModeLabels[$attributes[self::ATTRIBUTE_NAME_SCHEMA_MODE]]
                    : \__('Default', 'graphql-api'),
                $className . '__title',
                \__('Who can access:', 'graphql-api')
            );
        }
        if ($content) {
            return $maybeSchemaModeContent . $content;
        }
        return $maybeSchemaModeContent . sprintf(
            '<em>%s</em>',
            \__('(not set)', 'graphql-api')
        );
    }
}
