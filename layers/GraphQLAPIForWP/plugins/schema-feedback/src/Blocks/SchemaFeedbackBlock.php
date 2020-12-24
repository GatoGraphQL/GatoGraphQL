<?php

declare(strict_types=1);

namespace GraphQLAPI\SchemaFeedback\Blocks;

use GraphQLAPI\GraphQLAPI\Blocks\AbstractControlBlock;
use GraphQLAPI\GraphQLAPI\Blocks\GraphQLByPoPBlockTrait;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLAPI\GraphQLAPI\BlockCategories\AbstractBlockCategory;
use GraphQLAPI\SchemaFeedback\BlockCategories\SchemaFeedbackBlockCategory;

/**
 * Schema Feedback block
 */
class SchemaFeedbackBlock extends AbstractControlBlock
{
    use GraphQLByPoPBlockTrait;

    public const ATTRIBUTE_NAME_FEEDBACK_MESSAGE = 'feedbackMessage';
    public const ATTRIBUTE_NAME_FEEDBACK_TYPE = 'feedbackType';

    public const ATTRIBUTE_VALUE_FEEDBACK_TYPE_NOTICE = 'notice';
    public const ATTRIBUTE_VALUE_FEEDBACK_TYPE_WARNING = 'warning';

    protected function getBlockName(): string
    {
        return 'schema-feedback';
    }

    protected function getBlockCategory(): ?AbstractBlockCategory
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var SchemaFeedbackBlockCategory
         */
        $blockCategory = $instanceManager->getInstance(SchemaFeedbackBlockCategory::class);
        return $blockCategory;
    }

    protected function registerCommonStyleCSS(): bool
    {
        return true;
    }

    protected function registerEditorCSS(): bool
    {
        return true;
    }

    protected function getBlockDataTitle(): string
    {
        return \__('Feedback for:', 'graphql-api-schema-feedback');
    }
    protected function getBlockContentTitle(): string
    {
        return \__('Feedback type and message:', 'graphql-api-schema-feedback');
    }
    protected function getBlockContent(array $attributes, string $content): string
    {
        $blockContentPlaceholder = <<<EOF
        <div class="%s">
            %s<br/>
            %s
        </div>
EOF;
        $feedbackType = $attributes[self::ATTRIBUTE_NAME_FEEDBACK_TYPE] ?? '';
        $feedbackTypeLabels = [
            self::ATTRIBUTE_VALUE_FEEDBACK_TYPE_NOTICE => \__('Notice', 'graphql-api-schema-feedback'),
            self::ATTRIBUTE_VALUE_FEEDBACK_TYPE_WARNING => \__('Warning', 'graphql-api-schema-feedback'),
        ];
        $feedbackMessage = $attributes[self::ATTRIBUTE_NAME_FEEDBACK_MESSAGE] ?? '';
        if (!$feedbackMessage) {
            $feedbackMessage = sprintf(
                '<em>%s</em>',
                \__('(not set)', 'graphql-api-schema-feedback')
            );
        }
        return sprintf(
            $blockContentPlaceholder,
            $this->getBlockClassName() . '__content',
            sprintf(
                \__('Type: %s', 'graphql-api-schema-feedback'),
                $feedbackTypeLabels[$feedbackType] ?? ''
            ),
            sprintf(
                \__('Message: %s', 'graphql-api-schema-feedback'),
                $feedbackMessage
            )
        );
    }
}
