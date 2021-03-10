<?php

declare(strict_types=1);

namespace GraphQLAPI\SchemaFeedback\Services\PostTypes;

use GraphQLAPI\GraphQLAPI\Services\PostTypes\AbstractPostType;
use GraphQLAPI\SchemaFeedback\Blocks\SchemaFeedbackBlock;
use GraphQLAPI\SchemaFeedback\ModuleResolvers\FunctionalityModuleResolver;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;

class GraphQLSchemaFeedbackListPostType extends AbstractPostType
{
    /**
     * Custom Post Type name
     */
    public const POST_TYPE = 'graphql-feedback-list';

    /**
     * Custom Post Type name
     *
     * @return string
     */
    protected function getPostType(): string
    {
        return self::POST_TYPE;
    }

    /**
     * Module that enables this PostType
     */
    protected function getEnablingModule(): ?string
    {
        return FunctionalityModuleResolver::SCHEMA_FEEDBACK;
    }

    /**
     * The position on which to add the CPT on the menu.
     */
    protected function getMenuPosition(): int
    {
        return 7;
    }

    /**
     * Custom post type name
     *
     * @return void
     */
    public function getPostTypeName(): string
    {
        return \__('Schema Feedback List', 'graphql-api-schema-feedback');
    }

    /**
     * Custom Post Type plural name
     *
     * @param bool $uppercase Indicate if the name must be uppercase (for starting a sentence) or, otherwise, lowercase
     * @return string
     */
    protected function getPostTypePluralNames(bool $uppercase): string
    {
        return \__('Schema Feedback Lists', 'graphql-api-schema-feedback');
    }

    /**
     * Indicate if, whenever this CPT is saved/updated,
     * the timestamp must be regenerated
     *
     * @return boolean
     */
    protected function regenerateTimestampOnSave(): bool
    {
        return true;
    }

    /**
     * Indicate if the excerpt must be used as the CPT's description and rendered when rendering the post
     *
     * @return boolean
     */
    public function usePostExcerptAsDescription(): bool
    {
        return true;
    }

    /**
     * Gutenberg templates to lock down the Custom Post Type to
     *
     * @return array
     */
    protected function getGutenbergTemplate(): array
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var SchemaFeedbackBlock
         */
        $schemaFeedbackBlock = $instanceManager->getInstance(SchemaFeedbackBlock::class);
        return [
            [$schemaFeedbackBlock->getBlockFullName()],
        ];
    }
}
