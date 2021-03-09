<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Blocks;

use GraphQLAPI\GraphQLAPI\Blocks\GraphQLByPoPBlockTrait;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockRenderingHelpers;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use GraphQLAPI\GraphQLAPI\BlockCategories\AbstractBlockCategory;
use GraphQLAPI\GraphQLAPI\BlockCategories\SchemaConfigurationBlockCategory;
use GraphQLAPI\GraphQLAPI\Services\Helpers\CPTUtils;
use WP_Post;

abstract class AbstractSchemaConfigPostListBlock extends AbstractBlock
{
    use GraphQLByPoPBlockTrait;

    protected function isDynamicBlock(): bool
    {
        return true;
    }

    protected function getBlockCategory(): ?AbstractBlockCategory
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /**
         * @var SchemaConfigurationBlockCategory
         */
        $blockCategory = $instanceManager->getInstance(SchemaConfigurationBlockCategory::class);
        return $blockCategory;
    }

    abstract protected function getAttributeName(): string;

    abstract protected function getPostType(): string;

    abstract protected function getHeader(): string;

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
        $postContentElems = $foundPostListIDs = [];
        if ($postListIDs = $attributes[$this->getAttributeName()] ?? []) {
            $instanceManager = InstanceManagerFacade::getInstance();
            /** @var BlockRenderingHelpers */
            $blockRenderingHelpers = $instanceManager->getInstance(BlockRenderingHelpers::class);
            /** @var CPTUtils */
            $cptUtils = $instanceManager->getInstance(CPTUtils::class);
            /**
             * @var WP_Post[]
             */
            $postObjects = \get_posts([
                'include' => $postListIDs,
                'posts_per_page' => -1,
                'post_type' => $this->getPostType(),
                'post_status' => [
                    'publish',
                    'draft',
                    'pending',
                ],
            ]);
            foreach ($postObjects as $postObject) {
                $foundPostListIDs[] = $postObject->ID;
                $postDescription = $cptUtils->getCustomPostDescription($postObject);
                $permalink = \get_permalink($postObject->ID);
                $postContentElems[] = ($permalink ?
                    \sprintf(
                        '<code><a href="%s">%s</a></code>',
                        $permalink,
                        $blockRenderingHelpers->getCustomPostTitle($postObject)
                    ) :
                    \sprintf(
                        '<code>%s</code>',
                        $blockRenderingHelpers->getCustomPostTitle($postObject)
                    )
                ) . ($postDescription ?
                    '<br/><small>' . $postDescription . '</small>'
                    : ''
                );
            }
            // If any ID was not retrieved as an object, it is a deleted post
            $notFoundPostListIDs = array_diff(
                $postListIDs,
                $foundPostListIDs
            );
            foreach ($notFoundPostListIDs as $notFoundPostID) {
                $postContentElems[] = \sprintf(
                    '<code>%s</code>',
                    \sprintf(
                        \__('Undefined item with ID %s', 'graphql-api'),
                        $notFoundPostID
                    )
                );
            }
        }
        $className = $this->getBlockClassName();
        return sprintf(
            $blockContentPlaceholder,
            $className,
            $className . '-front',
            $this->getHeader(),
            $postContentElems ?
                sprintf(
                    '<ul><li>%s</li></ul>',
                    implode('</li><li>', $postContentElems)
                )
                : sprintf(
                    '<em>%s</em>',
                    \__('(not set)', 'graphql-api')
                )
        );
    }
    /**
     * Register index.css
     *
     * @return boolean
     */
    protected function registerEditorCSS(): bool
    {
        return true;
    }

    /**
     * Register style-index.css
     * It contains the styles for the list of elements
     *
     * @return boolean
     */
    protected function registerCommonStyleCSS(): bool
    {
        return true;
    }
}
