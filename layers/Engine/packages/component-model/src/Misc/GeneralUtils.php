<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Misc;

class GeneralUtils
{
    /**
     * @see Taken from http://stackoverflow.com/questions/4356289/php-random-string-generator
     */
    public static function generateRandomString(int $length = 6, bool $addtime = true, string $characters = 'abcdefghijklmnopqrstuvwxyz'): string
    {
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        if ($addtime) {
            $randomString .= time();
        }
        return $randomString;
    }

    /**
     * @return mixed[]
     *
     * @see https://gist.github.com/SeanCannon/6585889
     */
    public static function arrayFlatten(mixed $items, bool $deep = false): array
    {
        if (!is_array($items)) {
            return [$items];
        }

        return array_reduce($items, function ($carry, $item) use ($deep): array {
            return array_merge($carry, $deep ? self::arrayFlatten($item) : (is_array($item) ? $item : [$item]));
        }, []);
    }

    /**
     * Add parameters "key" => "value" to the URL
     *
     * @param array<string,mixed> $keyValues
     * @see https://stackoverflow.com/a/5809881
     */
    public static function addQueryArgs(array $keyValues, string $urlOrURLPath): string
    {
        if (!$keyValues) {
            return $urlOrURLPath;
        }

        $url_parts = parse_url($urlOrURLPath);
        if (!is_array($url_parts)) {
            return $urlOrURLPath;
        }

        $params = [];
        if (isset($url_parts['query'])) {
            parse_str($url_parts['query'], $params);
        }

        $params = array_merge(
            $params,
            $keyValues
        );

        // Note that this will url_encode all values
        $query = http_build_query($params);

        // Check if schema/host are present, because the URL can also be a relative path: /some-path/
        $scheme = isset($url_parts['scheme']) ? $url_parts['scheme'] . '://' : '';
        $host = $url_parts['host'] ?? '';
        $port = isset($url_parts['port']) && $url_parts['port'] ? (($url_parts['port'] == "80") ? "" : (":" . $url_parts['port'])) : '';
        $path = $url_parts['path'] ?? '';
        return $scheme . $host . $port . $path . ($query ? '?' . $query : '');
    }

    /**
     * Remove parameters from the URL
     *
     * @param string[] $keys
     * @see https://stackoverflow.com/a/5809881
     */
    public static function removeQueryArgs(array $keys, string $urlOrURLPath): string
    {
        if (!$keys) {
            return $urlOrURLPath;
        }

        $url_parts = parse_url($urlOrURLPath);
        if (!is_array($url_parts)) {
            return $urlOrURLPath;
        }

        /** @var array<string,mixed> */
        $params = [];
        if (isset($url_parts['query'])) {
            parse_str($url_parts['query'], $params);
        }

        // Only keep the keys which must not be removed
        $params = array_filter(
            $params,
            fn (string $param): bool => !in_array($param, $keys),
            ARRAY_FILTER_USE_KEY
        );

        // Note that this will url_encode all values
        $query = http_build_query($params);

        // Check if schema/host are present, because the URL can also be a relative path: /some-path/
        $scheme = isset($url_parts['scheme']) ? $url_parts['scheme'] . '://' : '';
        $host = $url_parts['host'] ?? '';
        $port = isset($url_parts['port']) && $url_parts['port'] ? (($url_parts['port'] == "80") ? "" : (":" . $url_parts['port'])) : '';
        $path = $url_parts['path'] ?? '';
        return $scheme . $host . $port . $path . ($query ? '?' . $query : '');
    }

    public static function maybeAddTrailingSlash(string $text): string
    {
        return rtrim($text, '/\\') . '/';
    }

    public static function getDomain(string $url, bool $withPort = false): string
    {
        $url_parts = parse_url($url);
        if (!is_array($url_parts)) {
            return $url;
        }
        $scheme = isset($url_parts['scheme']) ? $url_parts['scheme'] . '://' : '';
        $host = $url_parts['host'] ?? '';
        $port = '';
        if ($withPort) {
            $port = $url_parts['port'] ?? '';
            if ($port) {
                $port = ':' . $port;
            }
        }
        return $scheme . $host . $port;
    }

    public static function getHost(string $url): string
    {
        $url_parts = parse_url($url);
        if (!is_array($url_parts)) {
            return '';
        }
        return $url_parts['host'] ?? '';
    }

    public static function removeDomain(string $url): string
    {
        return substr($url, strlen(self::getDomain($url, true)));
    }

    public static function getPath(string $url): string
    {
        $url_parts = parse_url($url);
        if (!is_array($url_parts)) {
            return $url;
        }
        $path = $url_parts['path'] ?? '';
        return $path;
    }

    /**
     * @param iterable<mixed> $iterable
     * @return mixed[]
     */
    public static function iterableToArray(iterable $iterable): array
    {
        if (is_array($iterable)) {
            return $iterable;
        }
        $array = [];
        array_push($array, ...$iterable);
        return $array;
    }

    /**
     * @return array<string,mixed>
     */
    public static function getURLQueryParams(string $url): array
    {
        $queryParams = [];
        if ($queryString = parse_url($url, PHP_URL_QUERY)) {
            parse_str($queryString, $queryParams);
        }
        /** @var array<string,mixed> */
        return $queryParams;
    }

    public static function getURLWithoutQueryParams(string $url): string
    {
        $paramsPos = strpos($url, '?');
        if ($paramsPos === false) {
            return $url;
        }
        return substr($url, 0, $paramsPos);
    }

    public static function slugify(
        string $text,
        string $delimiter = '-',
        bool $makeLowerCase = true,
    ): string {
        $slug = trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $text))))), $delimiter);
        if ($makeLowerCase) {
            return strtolower($slug);
        }
        return $slug;
    } 
}
