<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Services\MenuPages;

use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;

use GatoGraphQL\GatoGraphQL\Services\MenuPages\AbstractSettingsMenuPage;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Misc\GeneralUtils;

abstract class AbstractExecuteActionWithCustomSettingsMenuPage extends AbstractSettingsMenuPage
{
    /**
     * The upstream method will print several <form> tags,
     * for the different settings categories.
     * 
     * Combine them all into a single <form> tag.
     */
    public function print(): void
    {
        ob_start();
        parent::print();
        $content = ob_get_clean();

        $content = str_replace(
            [
                '<form method="post" action="options.php">',
                '</form>',
            ],
            '',
            $content
        );

        $bulkActionOriginURL = App::request('bulk_action_origin_url') ?? App::query('bulk_action_origin_url') ?? '';
        if ($bulkActionOriginURL) {
            $bulkActionOriginURL = rawurldecode($bulkActionOriginURL);
        }

        $queryParams = GeneralUtils::getURLQueryParams($bulkActionOriginURL);
        $bulkActionName = $queryParams['action'] ?? $queryParams['action2'] ?? '';
        $bulkActionSelectedIdsString = App::request('bulk_action_selected_ids') ?? App::query('bulk_action_selected_ids') ?? '';
        $bulkActionSelectedIds = empty($bulkActionSelectedIdsString)
            ? []
            : explode(',', $bulkActionSelectedIdsString);

        $bulkActionSelectedIdsCount = count($bulkActionSelectedIds);
        if ($bulkActionSelectedIdsCount === 0) {
            printf(
                '<div class="notice notice-warning is-dismissible"><p>%s</p></div>',
                __('No IDs were selected.', 'gato-ai-translations-for-polylang')
            );
        } else {
            printf(
                '<div class="notice notice-info is-dismissible"><p>%s</p></div>',
                sprintf(
                    __('The following IDs were selected: <strong>%s</strong>', 'gato-ai-translations-for-polylang'),
                    implode('</strong>, <strong>', $bulkActionSelectedIds)
                )
            );
        }

        $bulkActionOriginURL = GeneralUtils::removeQueryArgs(
            [
                '_wpnonce',
                '_wp_http_referer',
                'action',
                'action2',
                'bulk_action_selected_ids',
                'bulk_action_origin_url',
                'bulk_action_origin_sendback_url',
            ],
            $bulkActionOriginURL
        );

        $sendbackURL = App::request('bulk_action_origin_sendback_url') ?? App::query('bulk_action_origin_sendback_url') ?? '';
        $sendbackURL = rawurldecode($sendbackURL);

        ?>
        <form method="post" action="<?php echo esc_url(home_url($bulkActionOriginURL)); ?>">
            <?php echo $content; ?>
            <?php /** Print the nonce field at the end!!! */ ?>
            <?php /** Because the previous form has the nonce fields, so override them! */ ?>
            <?php wp_nonce_field( 'bulk-' . 'posts' ); ?>
            <input type="hidden" name="action" value="<?php echo esc_attr($bulkActionName); ?>" />
            <input type="hidden" name="action2" value="<?php echo esc_attr($bulkActionName); ?>" />
            <input type="hidden" name="bulk_action_origin_sendback_url" value="<?php echo esc_attr($sendbackURL); ?>" />
            <input type="hidden" name="execute_action" value="1" />
            </form>
        <?php
    }

    /**
     * Get the settings items which have this target
     *
     * @return array<array<string,mixed>>
     */
    protected function getSettingsItems(): array
    {
        $upstreamSettingsItems = parent::getSettingsItems();
        $settingsItems = [];
        foreach ($upstreamSettingsItems as $settingItem) {
            $settingItem['settings'] = array_values(array_filter(
                $settingItem['settings'] ?? [],
                fn (array $item) => array_intersect(
                    $this->getPossibleTargets(),
                    $item[Properties::ADDITIONAL_TARGETS] ?? []
                ) !== []
            ));
            if ($settingItem['settings'] === []) {
                continue;
            }
            $settingsItems[] = $settingItem;
        }
        return $settingsItems;
    }

    /**
    * @return string[]
    */
    abstract protected function getPossibleTargets(): array;

    /**
     * Get the submit button label for a settings category
     */
    protected function getSubmitButtonLabel($settingsCategoryResolver, string $settingsCategory): string
    {
        return $this->getActionName();
    }

    abstract protected function getActionName(): string;
}
