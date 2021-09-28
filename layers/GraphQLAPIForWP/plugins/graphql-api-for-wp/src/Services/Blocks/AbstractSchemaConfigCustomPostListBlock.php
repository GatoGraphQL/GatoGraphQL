<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Blocks;

use Symfony\Contracts\Service\Attribute\Required;
use GraphQLAPI\GraphQLAPI\Registries\ModuleRegistryInterface;
use GraphQLAPI\GraphQLAPI\Security\UserAuthorizationInterface;
use GraphQLAPI\GraphQLAPI\Services\BlockCategories\SchemaConfigurationBlockCategory;
use GraphQLAPI\GraphQLAPI\Services\Helpers\BlockRenderingHelpers;
use GraphQLAPI\GraphQLAPI\Services\Helpers\CPTUtils;
use GraphQLAPI\GraphQLAPI\Services\Helpers\EditorHelpers;
use GraphQLAPI\GraphQLAPI\Services\Helpers\GeneralUtils;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use WP_Post;

abstract class AbstractSchemaConfigCustomPostListBlock extends AbstractSchemaConfigBlock
{
    protected BlockRenderingHelpers $blockRenderingHelpers;
    protected CPTUtils $cptUtils;

    #[Required]
    public function autowireAbstractSchemaConfigCustomPostListBlock(
        BlockRenderingHelpers $blockRenderingHelpers,
        CPTUtils $cptUtils,
    ) {
        $this->blockRenderingHelpers = $blockRenderingHelpers;
        $this->cptUtils = $cptUtils;
    }

    abstract protected function getAttributeName(): string;

    abstract protected function getCustomPostType(): string;

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
            /**
             * @var WP_Post[]
             */
            $postObjects = \get_posts([
                'include' => $postListIDs,
                'posts_per_page' => -1,
                'post_type' => $this->getCustomPostType(),
                'post_status' => [
                    'publish',
                    'draft',
                    'pending',
                ],
            ]);
            foreach ($postObjects as $postObject) {
                $foundPostListIDs[] = $postObject->ID;
                $postDescription = $this->cptUtils->getCustomPostDescription($postObject);
                $permalink = \get_permalink($postObject->ID);
                $postContentElems[] = ($permalink ?
                    \sprintf(
                        '<code><a href="%s">%s</a></code>',
                        $permalink,
                        $this->blockRenderingHelpers->getCustomPostTitle($postObject)
                    ) :
                    \sprintf(
                        '<code>%s</code>',
                        $this->blockRenderingHelpers->getCustomPostTitle($postObject)
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
     */
    protected function registerEditorCSS(): bool
    {
        return true;
    }

    /**
     * Register style-index.css
     * It contains the styles for the list of elements
     */
    protected function registerCommonStyleCSS(): bool
    {
        return true;
    }
}
