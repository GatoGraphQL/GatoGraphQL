<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

use GatoGraphQL\GatoGraphQL\Enums\URLPathSettingType;
use PoP\Root\Services\AbstractBasicService;
use WP_Post;
use WP_Rewrite;

use function __;
use function array_map;
use function array_merge;
use function array_unique;
use function explode;
use function get_page_by_path;
use function implode;
use function in_array;
use function preg_match_all;
use function rest_get_url_prefix;
use function sprintf;
use function str_contains;
use function strlen;
use function trim;

class URLPathSettingValidator extends AbstractBasicService implements URLPathSettingValidatorInterface
{
    /**
     * A path segment longer than 255 characters is rejected
     * by the webserver (it returns a 403), so keep the whole
     * path well below that
     */
    public final const MAX_LENGTH = 200;

    public function normalizeURLPath(string $urlPath): string
    {
        return trim(trim($urlPath), '/');
    }

    public function getURLPathError(string $urlPath, URLPathSettingType $urlPathSettingType): ?string
    {
        if ($urlPath === '') {
            return __('it is empty', 'gatographql');
        }

        /**
         * WordPress adds the path to the rewrite rules as a regex,
         * without escaping it, so a character such as "." or "*"
         * makes the endpoint match other URLs (eg: path ".*" makes
         * every URL in the site be handled by the endpoint).
         *
         * Characters such as "#", "?" or "%", and non-ASCII ones
         * (which are percent-encoded in the requested URL), make
         * the endpoint unreachable.
         */
        if (preg_match_all('#[^A-Za-z0-9_\-/]#u', $urlPath, $matches) > 0) {
            return sprintf(
                __('it contains unsupported characters (%s). Use only letters, numbers, "-" and "_", and "/" to separate the parts of the path', 'gatographql'),
                implode(
                    ', ',
                    array_map(
                        fn (string $char) => sprintf('"%s"', $char),
                        array_unique($matches[0])
                    )
                )
            );
        }

        if (str_contains($urlPath, '//')) {
            return __('it contains "//"', 'gatographql');
        }

        if (strlen($urlPath) > self::MAX_LENGTH) {
            return sprintf(
                __('it is longer than %s characters', 'gatographql'),
                self::MAX_LENGTH
            );
        }

        $restURLPrefix = trim(rest_get_url_prefix(), '/');
        $firstSegment = explode('/', $urlPath)[0];
        if ($firstSegment === $restURLPrefix) {
            return sprintf(
                __('the paths under "%s" are handled by the WordPress REST API', 'gatographql'),
                $restURLPrefix
            );
        }

        if ($urlPathSettingType !== URLPathSettingType::ENDPOINT) {
            return null;
        }

        /**
         * These folders are served by the webserver
         * directly, without loading WordPress
         */
        if (in_array($urlPath, ['wp-admin', 'wp-content', 'wp-includes'])) {
            return sprintf(
                __('"%s" is a WordPress folder', 'gatographql'),
                $urlPath
            );
        }

        if (in_array($urlPath, $this->getFeedNames())) {
            return sprintf(
                __('"%s" is used by the WordPress feeds', 'gatographql'),
                $urlPath
            );
        }

        return null;
    }

    /**
     * @return string[]
     */
    protected function getFeedNames(): array
    {
        global $wp_rewrite;
        /** @var WP_Rewrite|null */
        $wpRewrite = $wp_rewrite;
        if ($wpRewrite === null) {
            return ['feed', 'rdf', 'rss', 'rss2', 'atom'];
        }
        /** @var string[] */
        $feeds = $wpRewrite->feeds;
        return array_merge(
            [$wpRewrite->feed_base],
            $feeds
        );
    }

    public function getPageUnderURLPath(string $urlPath): ?WP_Post
    {
        /** @var WP_Post|null */
        $page = get_page_by_path($urlPath);
        return $page;
    }
}
