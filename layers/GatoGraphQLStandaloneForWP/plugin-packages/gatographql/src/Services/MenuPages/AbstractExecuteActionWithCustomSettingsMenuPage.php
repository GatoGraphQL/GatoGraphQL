<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Services\MenuPages;

use GatoGraphQLStandalone\GatoGraphQL\Constants\Params;
use GatoGraphQL\GatoGraphQL\ModuleSettings\Properties;
use GatoGraphQL\GatoGraphQL\Services\MenuPages\AbstractSettingsMenuPage;
use GatoGraphQL\GatoGraphQL\SettingsCategoryResolvers\SettingsCategoryResolverInterface;
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

        if ($content === false) {
            return;
        }

        $content = str_replace(
            [
                '<form method="post" action="options.php">',
                '</form>',
            ],
            '',
            $content
        );

        // Remove the inputs from the content that will be overridden
        $inputNamesToRemove = [
            '_wpnonce',
            '_wp_http_referer',
            'action',
            'action2',
            self::FORM_ORIGIN,
        ];
        $inputNamesPattern = implode('|', array_map('preg_quote', $inputNamesToRemove));
        $content = preg_replace(
            '/<input[^>]+name="(' . $inputNamesPattern . ')"[^>]*>/',
            '',
            $content
        );

        /** @var string */
        $bulkActionOriginURL = App::request(Params::BULK_ACTION_ORIGIN_URL) ?? App::query(Params::BULK_ACTION_ORIGIN_URL) ?? '';
        if ($bulkActionOriginURL) {
            $bulkActionOriginURL = rawurldecode($bulkActionOriginURL);
        }

        /** @var string */
        $originRequestParamsAsString = App::request(Params::BULK_ACTION_ORIGIN_REQUEST_PARAMS) ?? App::query(Params::BULK_ACTION_ORIGIN_REQUEST_PARAMS) ?? '';
        if ($originRequestParamsAsString) {
            $originRequestParamsAsString = rawurldecode($originRequestParamsAsString);
        }

        $originRequestParams = GeneralUtils::getURLQueryParams($originRequestParamsAsString);
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

        /** @var string */
        $sendbackURL = App::request(Params::BULK_ACTION_ORIGIN_SENDBACK_URL) ?? App::query(Params::BULK_ACTION_ORIGIN_SENDBACK_URL) ?? '';
        $sendbackURL = rawurldecode($sendbackURL);

        ?>
        <form method="post" action="<?php echo esc_url(home_url($bulkActionOriginURL)); ?>">
            <?php echo $content; ?>

            <?php /** Re-add all the same inputs as in the request (that includes the nonce, and the action) */ ?>
            <?php
            foreach ($originRequestParams as $key => $value) {
                if ($value === null || is_object($value)) {
                    continue;
                }
                if (is_array($value)) {
                    foreach ($value as $subValue) {
                        ?>
                        <input type="hidden" name="<?php echo esc_attr($key); ?>[]" value="<?php echo esc_attr($subValue); ?>" />
                        <?php
                    }
                    continue;
                }
                ?>
                <input type="hidden" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($value); ?>" />
                <?php
            } ?>

            <?php /** Print all these inputs below at the end!!! */ ?>
            <?php /** Because the previous form has these same fields, override them! */ ?>

            <?php /** Because fields belong to different forms, unify them under a new origin */ ?>
            <input type="hidden" name="<?php echo esc_attr(self::FORM_ORIGIN) ?>" value="<?php echo esc_attr($this->getFormOrigin()) ?>" />
            
            <?php /** Point to the same bulk action, but adding "execute_action" to the query params */ ?>
            <input type="hidden" name="<?php echo Params::BULK_ACTION_EXECUTE ?>" value="1" />

            <?php /** Add the original sendback URL */ ?>
            <input type="hidden" name="<?php echo Params::BULK_ACTION_ORIGIN_SENDBACK_URL ?>" value="<?php echo esc_attr($sendbackURL); ?>" />
            
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

    abstract protected function getFormOrigin(): string;

    /**
     * Get the submit button label for a settings category
     */
    protected function getSubmitButtonLabel(SettingsCategoryResolverInterface $settingsCategoryResolver, string $settingsCategory): string
    {
        return $this->getActionName();
    }

    abstract protected function getActionName(): string;
}
