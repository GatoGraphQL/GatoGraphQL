<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use PoP\Root\App;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\AccessControlBlockCategory;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\BlockCategoryInterface;
use PoP\AccessControl\Module;
use PoP\AccessControl\ModuleConfiguration;
use PoP\AccessControl\Schema\SchemaModes;

/**
 * Access Control block
 */
class AccessControlBlock extends AbstractControlBlock
{
    use MainPluginBlockTrait;

    public final const ATTRIBUTE_NAME_SCHEMA_MODE = 'schemaMode';

    private ?AccessControlBlockCategory $accessControlBlockCategory = null;

    final public function setAccessControlBlockCategory(AccessControlBlockCategory $accessControlBlockCategory): void
    {
        $this->accessControlBlockCategory = $accessControlBlockCategory;
    }
    final protected function getAccessControlBlockCategory(): AccessControlBlockCategory
    {
        return $this->accessControlBlockCategory ??= $this->instanceManager->getInstance(AccessControlBlockCategory::class);
    }

    protected function getBlockName(): string
    {
        return 'access-control';
    }

    protected function getBlockCategory(): ?BlockCategoryInterface
    {
        return $this->getAccessControlBlockCategory();
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
        /** @var ModuleConfiguration */
        $componentConfiguration = App::getComponent(Module::class)->getConfiguration();
        if ($componentConfiguration->enableIndividualControlForPublicPrivateSchemaMode()) {
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
        /** @var ModuleConfiguration */
        $componentConfiguration = App::getComponent(Module::class)->getConfiguration();
        return array_merge(
            parent::getLocalizedData(),
            [
                'isIndividualControlForSchemaModeEnabled' => $componentConfiguration->enableIndividualControlForPublicPrivateSchemaMode(),
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
        /** @var ModuleConfiguration */
        $componentConfiguration = App::getComponent(Module::class)->getConfiguration();
        if ($componentConfiguration->enableIndividualControlForPublicPrivateSchemaMode()) {
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
