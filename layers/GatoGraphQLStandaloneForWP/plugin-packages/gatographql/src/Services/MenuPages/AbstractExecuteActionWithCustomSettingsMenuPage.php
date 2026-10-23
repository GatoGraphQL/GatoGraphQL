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

use function __;
use function array_key_first;
use function basename;
use function esc_html;
use function esc_url;
use function get_post_type_object;
use function get_taxonomy;
use function home_url;
use function is_string;
use function parse_str;
use function parse_url;
use function preg_match;
use function sprintf;
use function submit_button;
use function wp_validate_redirect;

abstract class AbstractExecuteActionWithCustomSettingsMenuPage extends AbstractSettingsMenuPage
{
    public final const SUBMIT_BUTTON_NAME = 'gatographql-execute-action';

    /**
     * The settings of all categories are submitted together, in
     * a single form, to execute the action with them
     */
    protected function printsSingleFormForAllCategories(): bool
    {
        return true;
    }

    /**
     * The upstream method prints the settings of all the categories,
     * without <form> tags. Wrap them all into a single <form> tag.
     */
    public function print(): void
    {
        ob_start();
        parent::print();
        $content = ob_get_clean();

        if ($content === false) {
            return;
        }

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

        $bulkActionOriginURL = $this->getBulkActionParam(Params::BULK_ACTION_ORIGIN_URL);

        /**
         * The query string arrives decoded once already, by PHP; decoding
         * it again would turn an encoded `&` or `#` inside a value into
         * a separator, and `parse_str` decodes the values by itself.
         */
        $originRequestParams = GeneralUtils::getURLQueryParams(
            $this->getBulkActionParam(Params::BULK_ACTION_ORIGIN_REQUEST_PARAMS)
        );

        // When filtering entries, if this input is present in the request, the bulk action will not be executed
        unset($originRequestParams['filter_action']);
        unset($originRequestParams['bulk_action']);

        $bulkActionSelectedIdsString = $this->getBulkActionParam(Params::BULK_ACTION_SELECTED_IDS);
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

        /**
         * Decoded once by PHP already, like the origin params above:
         * a `%2B` in the screen's search would otherwise come back as
         * a space.
         */
        $sendbackURL = $this->getBulkActionParam(Params::BULK_ACTION_ORIGIN_SENDBACK_URL);

        ?>
        <form method="post" action="<?php echo esc_url(home_url($bulkActionOriginURL)); ?>">
            <?php echo $content; ?>

            <?php /** Re-add all the same inputs as in the request (that includes the nonce, and the action) */ ?>
            <?php
            foreach ($originRequestParams as $key => $value) {
                $this->printHiddenInputs((string) $key, $value);
            }
            $this->printAdditionalHiddenInputs($bulkActionSelectedIds);
            ?>

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
     * The bar at the bottom of the page: back to the screen the
     * entities were selected on, and the button to run the action
     */
    protected function printPageFooter(): void
    {
        $settingsCategoryRegistry = $this->getSettingsCategoryRegistry();
        $settingsCategoryResolvers = $settingsCategoryRegistry->getSettingsCategorySettingsCategoryResolvers();
        $settingsCategory = (string) array_key_first($settingsCategoryResolvers);
        $settingsCategoryResolver = $settingsCategoryResolvers[$settingsCategory] ?? null;
        $backURL = $this->getOriginScreenURL();
        $backLabel = $backURL === '' ? '' : sprintf(
            __('Back to %s', 'gatographql'),
            $this->getOriginScreenLabel($backURL)
        );
        ?>
        <div class="gato-savebar gato-runbar">
            <span class="gato-savebar__status">
                <?php if ($backURL !== '') : ?>
                    <a href="<?php echo esc_url($backURL) ?>" class="gato-runbar__back"><span aria-hidden="true">← </span><?php echo esc_html($backLabel) ?></a>
                <?php endif; ?>
            </span>
            <span class="gato-savebar__actions">
                <?php
                if ($settingsCategoryResolver !== null) {
                    submit_button(
                        $this->getSubmitButtonLabel($settingsCategoryResolver, $settingsCategory),
                        'primary',
                        $this->getSubmitButtonName($settingsCategoryResolver, $settingsCategory),
                        false
                    );
                }
                ?>
            </span>
        </div>
        <?php
    }

    /**
     * The screen the entities were selected on, to go back to
     */
    protected function getOriginScreenURL(): string
    {
        $url = $this->getBulkActionParam(Params::BULK_ACTION_ORIGIN_SENDBACK_URL);
        if ($url === '') {
            $url = $this->getBulkActionParam(Params::BULK_ACTION_ORIGIN_URL);
        }
        if ($url === '') {
            return '';
        }
        if (!preg_match('#^https?://#', $url)) {
            $url = home_url($url);
        }
        return wp_validate_redirect($url, '');
    }

    /**
     * The name of the screen the entities were selected on: the post
     * type or taxonomy listed, or else the screen's own name
     */
    protected function getOriginScreenLabel(string $url): string
    {
        $path = (string) parse_url($url, PHP_URL_PATH);
        $query = [];
        parse_str((string) parse_url($url, PHP_URL_QUERY), $query);
        $screen = basename($path);
        if ($screen === 'edit.php') {
            $postType = is_string($query['post_type'] ?? null) ? $query['post_type'] : 'post';
            $postTypeObject = get_post_type_object($postType);
            if ($postTypeObject !== null) {
                return $postTypeObject->labels->name;
            }
        }
        if ($screen === 'edit-tags.php' || $screen === 'term.php') {
            $taxonomy = is_string($query['taxonomy'] ?? null) ? $query['taxonomy'] : 'post_tag';
            $taxonomyObject = get_taxonomy($taxonomy);
            if ($taxonomyObject !== false) {
                return $taxonomyObject->labels->name;
            }
        }
        if ($screen === 'users.php') {
            return __('Users', 'gatographql');
        }
        if ($screen === 'upload.php') {
            return __('Media', 'gatographql');
        }
        return __('the previous screen', 'gatographql');
    }

    /**
     * The bulk action params reach this page in the URL the origin
     * screen linked to, and are posted back by its own form.
     */
    protected function getBulkActionParam(string $name): string
    {
        /** @var string */
        return App::request($name) ?? App::query($name) ?? '';
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
            implode('</strong>, <strong>', array_map('esc_html', $bulkActionSelectedIds))
        );
    }

    /**
     * Hidden inputs to post back on top of those of the origin request.
     * The selected IDs travel in the URL on their own already, so a screen
     * can leave its own selection param out of the origin params it
     * carries there (see `getExecuteActionWithCustomSettingsPageURL()`),
     * halving what a large selection adds to the URL, and re-create that
     * param here.
     *
     * @param string[] $bulkActionSelectedIds
     */
    protected function printAdditionalHiddenInputs(array $bulkActionSelectedIds): void
    {
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
