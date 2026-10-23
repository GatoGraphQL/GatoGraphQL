<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\Constants\HTMLCodes;
use GatoGraphQL\GatoGraphQL\ContentPrinters\CollapsibleContentPrinterTrait;
use WP_Post;

use function get_posts;

trait CommonModuleResolverTrait
{
    use CollapsibleContentPrinterTrait;

    /**
     * @param string[]|null $applicableItems
     */
    protected function getDefaultValueDescription(
        string $blockTitle,
        ?array $applicableItems = null
    ): string {
        $applicableItems ??= $this->getDefaultValueApplicableItems($blockTitle);
        return $this->getCollapsible(
            sprintf(
                '%s<ul><li>%s</li></ul>',
                \__('This value is the default, which a Schema Configuration can override. The endpoint uses it when:', 'gatographql'),
                implode(
                    '</li><li>',
                    $applicableItems
                )
            ),
        );
    }

    /**
     * @return string[]
     */
    protected function getDefaultValueApplicableItems(string $blockTitle): array
    {
        return [
            \__('It has no Schema Configuration', 'gatographql'),
            \__('Its Schema Configuration is <code>"None"</code>', 'gatographql'),
            sprintf(
                \__('Its Schema Configuration does not have block <code>%s</code>', 'gatographql'),
                $blockTitle
            ),
            \__('That block in its Schema Configuration has value <code>"Default"</code>', 'gatographql')
        ];
    }

    protected function getPressCtrlToSelectMoreThanOneOptionLabel(): string
    {
        return \__('Press <code>ctrl</code> or <code>shift</code> keys to select more than one.', 'gatographql');
    }

    protected function getSettingsInfoContent(string $content): string
    {
        return sprintf(
            '<span class="settings-info">%s</span>',
            $content
        );
    }

    /**
     * @return WP_Post[]
     */
    protected function getSchemaEntityListCustomPosts(string $customPostType): array
    {
        return get_posts([
            'posts_per_page' => -1,
            'post_type' => $customPostType,
            'post_status' => 'publish',
        ]);
    }

    protected function getSettingsItemHelpLinkHTML(
        string $url,
        string $title
    ): string {
        $helpLabelPlaceholder = '<a href="%1$s" title="' . \__('\'%2$s\' on the website', 'gatographql') . '" target="_blank">' . \__('Help', 'gatographql') . HTMLCodes::OPEN_IN_NEW_WINDOW . '</a>';
        return sprintf(
            $helpLabelPlaceholder,
            $url,
            $title
        );
    }
}
