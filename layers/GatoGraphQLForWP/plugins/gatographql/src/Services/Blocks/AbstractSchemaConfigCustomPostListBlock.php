<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\Blocks;

use GatoGraphQL\GatoGraphQL\Module;
use GatoGraphQL\GatoGraphQL\ModuleConfiguration;
use GatoGraphQL\GatoGraphQL\Services\Blocks\AbstractSchemaConfigCustomizableConfigurationBlock;
use GatoGraphQL\GatoGraphQL\Services\Helpers\BlockRenderingHelpers;
use GatoGraphQL\GatoGraphQL\Services\Helpers\CPTUtils;
use PoP\Root\App;
use WP_Post;

abstract class AbstractSchemaConfigCustomPostListBlock extends AbstractSchemaConfigCustomizableConfigurationBlock
{
    private ?BlockRenderingHelpers $blockRenderingHelpers = null;
    private ?CPTUtils $cptUtils = null;

    final protected function getBlockRenderingHelpers(): BlockRenderingHelpers
    {
        if ($this->blockRenderingHelpers === null) {
            /** @var BlockRenderingHelpers */
            $blockRenderingHelpers = $this->instanceManager->getInstance(BlockRenderingHelpers::class);
            $this->blockRenderingHelpers = $blockRenderingHelpers;
        }
        return $this->blockRenderingHelpers;
    }
    final protected function getCPTUtils(): CPTUtils
    {
        if ($this->cptUtils === null) {
            /** @var CPTUtils */
            $cptUtils = $this->instanceManager->getInstance(CPTUtils::class);
            $this->cptUtils = $cptUtils;
        }
        return $this->cptUtils;
    }

    abstract protected function getAttributeName(): string;

    abstract protected function getCustomPostType(): string;

    /**
     * Print the list of all the contained Access Control blocks
     *
     * @param array<string,mixed> $attributes
     */
    protected function doRenderBlock(array $attributes, string $content): string
    {
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
            $blockRenderingHelpers = $this->getBlockRenderingHelpers();
            $cptUtils = $this->getCPTUtils();
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
                        \__('Undefined item with ID %s', 'gatographql'),
                        $notFoundPostID
                    )
                );
            }
        }
        return $this->getBlockSectionContentHTML(
            $this->getHeader(),
            $postContentElems ?
                sprintf(
                    '<ul><li>%s</li></ul>',
                    implode('</li><li>', $postContentElems)
                )
                : $this->getNoItemsSelectedLabelHTML()
        );
    }

    protected function getNoItemsSelectedLabelHTML(): string
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return sprintf(
            '<em>%s</em>',
            $moduleConfiguration->getNoItemsSelectedLabel()
        );
    }

    protected function getBlockSectionContentHTML(
        string $sectionHeader,
        string $sectionContent,
    ): string {
        $blockContentPlaceholder = '<div class="%s"><h4 class="%s">%s</strong></h4>%s</div>';
        $className = $this->getBlockClassName();
        return sprintf(
            $blockContentPlaceholder,
            $className,
            $className . '-front',
            $sectionHeader,
            $sectionContent
        );
    }

    abstract protected function getHeader(): string;

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
