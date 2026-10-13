<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

use GatoGraphQL\GatoGraphQL\Enums\URLPathSettingType;
use WP_Post;

/**
 * Validate the Settings options that modify permalinks (such as
 * the path of the single endpoint, or the base slug of the
 * persisted queries), which could otherwise break the endpoint,
 * or the site
 */
interface URLPathSettingValidatorInterface
{
    /**
     * Remove the whitespaces around the URL path, and the
     * "/" on both ends, as these are added when needed
     */
    public function normalizeURLPath(string $urlPath): string;

    /**
     * @param string $urlPath The normalized URL path
     * @return string|null Why the URL path is not valid, or `null` if it is valid
     */
    public function getURLPathError(string $urlPath, URLPathSettingType $urlPathSettingType): ?string;

    /**
     * The page under the same URL path, which an endpoint
     * under this path would make inaccessible
     *
     * @param string $urlPath The normalized URL path
     */
    public function getPageUnderURLPath(string $urlPath): ?WP_Post;
}
