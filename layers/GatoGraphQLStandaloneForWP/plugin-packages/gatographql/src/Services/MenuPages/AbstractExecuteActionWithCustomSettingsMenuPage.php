<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Services\MenuPages;

use GatoGraphQLStandalone\GatoGraphQL\Constants\FormOrigins;
use GatoGraphQLStandalone\GatoGraphQL\Constants\Params;
use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\AbstractSettingsMenuPage;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Configuration\RequestHelpers;
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

        $bulkActionOriginURL = App::request(Params::BULK_ACTION_ORIGIN_URL) ?? App::query(Params::BULK_ACTION_ORIGIN_URL) ?? '';
        if ($bulkActionOriginURL) {
            $bulkActionOriginURL = rawurldecode($bulkActionOriginURL);
        }

        $queryParams = GeneralUtils::getURLQueryParams($bulkActionOriginURL);
        $bulkActionSelectedIdsString = App::request(Params::BULK_ACTION_SELECTED_IDS) ?? App::query(Params::BULK_ACTION_SELECTED_IDS) ?? '';
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
                Params::BULK_ACTION_SELECTED_IDS,
                Params::BULK_ACTION_ORIGIN_URL,
                Params::BULK_ACTION_ORIGIN_SENDBACK_URL,
            ],
            $bulkActionOriginURL
        );

        $sendbackURL = App::request(Params::BULK_ACTION_ORIGIN_SENDBACK_URL) ?? App::query(Params::BULK_ACTION_ORIGIN_SENDBACK_URL) ?? '';
        $sendbackURL = rawurldecode($sendbackURL);

        ?>
        <form method="post" action="<?php echo esc_url(home_url($bulkActionOriginURL)); ?>">
            <?php echo $content; ?>

            <?php /** Print all these inputs below at the end!!! */ ?>
            <?php /** Because the previous form has these same fields, override them! */ ?>

            <?php /** Because fields belong to different forms, unify them under a new origin */ ?>
            <input type="hidden" name="<?php echo esc_attr(self::FORM_ORIGIN) ?>" value="<?php echo esc_attr($this->getFormOrigin()) ?>" />
            
            <?php /** Use the same nonce fields as the previous form */ ?>
            <input type="hidden" name="_wpnonce" value="<?php echo esc_attr($queryParams['_wpnonce'] ?? ''); ?>" />
            <input type="hidden" name="_wp_http_referer" value="<?php echo esc_attr($queryParams['_wp_http_referer'] ?? ''); ?>" />
            
            <?php /** Point to the same bulk action, but adding "execute_action" to the query params */ ?>
            <input type="hidden" name="action" value="<?php echo esc_attr($queryParams['action'] ?? ''); ?>" />
            <input type="hidden" name="action2" value="<?php echo esc_attr($queryParams['action2'] ?? ''); ?>" />
            <input type="hidden" name="execute_action" value="1" />

            <?php /** Add the original sendback URL */ ?>
            <input type="hidden" name="bulk_action_origin_sendback_url" value="<?php echo esc_attr($sendbackURL); ?>" />
            
            <?php /** Support for XDebug */ ?>
            <?php RequestHelpers::maybePrintXDebugInputsInForm() ?>
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
                    $item[Properties::ADDITIONAL_FORM_TARGETS] ?? []
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
    protected function getPossibleTargets(): array
    {
        return [
            $this->getFormOrigin(),
        ];
    }

    /**
     * @return string
     */
    abstract protected function getFormOrigin(): string;

    /**
     * Get the submit button label for a settings category
     */
    protected function getSubmitButtonLabel($settingsCategoryResolver, string $settingsCategory): string
    {
        return $this->getActionName();
    }

    abstract protected function getActionName(): string;
}
