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
    public final const SUBMIT_BUTTON_NAME = 'gatographql-execute-action';

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

            // When filtering entries, if this input is present in the request, the bulk action will not be executed
            'filter_action',
            'bulk_action',

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

        /**
         * The query string arrives decoded once already, by PHP; decoding
         * it again would turn an encoded `&` or `#` inside a value into
         * a separator, and `parse_str` decodes the values by itself.
         *
         * @var string
         */
        $originRequestParamsAsString = App::request(Params::BULK_ACTION_ORIGIN_REQUEST_PARAMS) ?? App::query(Params::BULK_ACTION_ORIGIN_REQUEST_PARAMS) ?? '';

        $originRequestParams = GeneralUtils::getURLQueryParams($originRequestParamsAsString);

        // When filtering entries, if this input is present in the request, the bulk action will not be executed
        unset($originRequestParams['filter_action']);
        unset($originRequestParams['bulk_action']);

        $bulkActionSelectedIdsString = App::request(Params::BULK_ACTION_SELECTED_IDS) ?? App::query(Params::BULK_ACTION_SELECTED_IDS) ?? '';
        $bulkActionSelectedIds = empty($bulkActionSelectedIdsString)
            ? []
            : explode(',', $bulkActionSelectedIdsString);

        if ($bulkActionSelectedIds === []) {
            printf(
                '<div class="notice notice-warning is-dismissible"><p>%s</p></div>',
                __('No IDs were selected.', 'gatographql')
            );
        } else {
            printf(
                '<div class="notice notice-info is-dismissible"><p>%s</p></div>',
                $this->getSelectedEntitiesNoticeMessage($bulkActionSelectedIds)
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
                $this->printHiddenInputs((string) $key, $value);
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
     * Entity IDs mean something to the user on most screens. Where they
     * do not (a screen keying its items by hash), the page can name the
     * items instead.
     *
     * @param string[] $bulkActionSelectedIds
     */
    protected function getSelectedEntitiesNoticeMessage(array $bulkActionSelectedIds): string
    {
        return sprintf(
            __('The following IDs were selected: <strong>%s</strong>', 'gatographql'),
            implode('</strong>, <strong>', $bulkActionSelectedIds)
        );
    }

    /**
     * An array value is printed as one input per leaf, nested keys and
     * all (`translation[en][hash]`), so that the request it came from is
     * reproduced exactly.
     */
    protected function printHiddenInputs(string $name, mixed $value): void
    {
        if ($value === null || is_object($value)) {
            return;
        }
        if (is_array($value)) {
            foreach ($value as $subKey => $subValue) {
                $this->printHiddenInputs($name . '[' . $subKey . ']', $subValue);
            }
            return;
        }
        ?>
        <input type="hidden" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr((string) $value); ?>" />
        <?php
    }

    /**
     * Get the settings items which have this target
     *
     * @return array<array<string,mixed>>
     */
    protected function doGetSettingsItems(): array
    {
        $upstreamSettingsItems = parent::doGetSettingsItems();
        $settingsItems = [];
        foreach ($upstreamSettingsItems as $settingItem) {
            $settingItem['settings'] = array_values(array_filter(
                $settingItem['settings'] ?? [],
                fn (array $item) => array_intersect(
                    $this->getPossibleTargets(),
                    $item[Properties::FORM_TARGETS] ?? []
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

    /**
     * This button runs the action on the selected entities; it does not
     * save these settings, which are carried along with the request and
     * apply to that run alone. Naming it `submit` would hand the screen it
     * posts back to a parameter that screen may already read as its own
     * "Save" button having been pressed.
     */
    protected function getSubmitButtonName(SettingsCategoryResolverInterface $settingsCategoryResolver, string $settingsCategory): string
    {
        return self::SUBMIT_BUTTON_NAME;
    }

    abstract protected function getActionName(): string;
}
