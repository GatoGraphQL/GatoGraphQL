<?php

declare(strict_types=1);

namespace GatoGraphQLStandalone\GatoGraphQL\Helpers;

use GatoGraphQLStandalone\GatoGraphQL\Constants\Params;
use PoP\ComponentModel\Configuration\RequestHelpers;
use PoP\ComponentModel\Constants\FrameworkParams;

use function admin_url;
use function array_diff_key;
use function array_flip;
use function http_build_query;
use function wp_unslash;

class ExecuteBulkActionHelper implements ExecuteBulkActionHelperInterface
{
    public function getExecuteActionWithCustomSettingsBulkActionName(string $bulkActionName): string
    {
        return $bulkActionName . '-custom';
    }

    /**
     * @param array<string|int> $entityIDs
     * @param string[] $requestParamsToSkip
     */
    public function getExecuteActionWithCustomSettingsPageURL(
        string $screenID,
        array $entityIDs,
        string $originURL,
        string $sendbackURL,
        array $requestParamsToSkip = [],
    ): string {
        $urlPlaceholder = 'admin.php?page=%s&%s=%s&%s=%s&%s=%s&%s=%s';
        if (RequestHelpers::isRequestingXDebug()) {
            $urlPlaceholder .= '&' . FrameworkParams::XDEBUG_TRIGGER . '=1';
        }

        /**
         * Preserve the $_REQUEST values, encoding the values too:
         * `add_query_arg()` only encodes the keys, and a value holding
         * a `&`, `=` or `#` (Polylang's Translations screen posts every
         * stored translation, HTML entities included) would otherwise
         * cut the query string short when it is parsed back.
         *
         * WordPress slashes the request, and the values are posted back
         * as a new request that WordPress slashes again, so they are
         * carried unslashed. The separator is given explicitly, as the
         * `arg_separator.output` ini setting may be `&amp;`, which
         * `parse_str` would read as part of the next key.
         *
         * The caller can leave out the params the bulk action does not
         * read, which travel in the URL and count against its length.
         */
        // phpcs:disable SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable.DisallowedSuperGlobalVariable
        // phpcs:disable Generic.PHP.DisallowRequestSuperglobal
        /** @var array<string,mixed> */
        $originRequestParamValues = wp_unslash(array_diff_key($_REQUEST, array_flip($requestParamsToSkip)));
        $originRequestParams = '?' . http_build_query($originRequestParamValues, '', '&');

        return admin_url(sprintf(
            $urlPlaceholder,
            $screenID,
            Params::BULK_ACTION_SELECTED_IDS,
            implode(',', $entityIDs),
            Params::BULK_ACTION_ORIGIN_URL,
            rawurlencode($originURL),
            Params::BULK_ACTION_ORIGIN_REQUEST_PARAMS,
            rawurlencode($originRequestParams),
            Params::BULK_ACTION_ORIGIN_SENDBACK_URL,
            rawurlencode($sendbackURL)
        ));
    }
}
