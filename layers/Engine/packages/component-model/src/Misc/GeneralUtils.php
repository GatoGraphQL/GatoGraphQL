<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Misc;

use PoP\ComponentModel\Error\Error;

class GeneralUtils
{
    // Taken from http://stackoverflow.com/questions/4356289/php-random-string-generator
    public static function generateRandomString($length = 6, $addtime = true, $characters = 'abcdefghijklmnopqrstuvwxyz')
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

    // Taken from https://gist.github.com/SeanCannon/6585889
    public static function arrayFlatten(mixed $items, $deep = false)
    {
        if (!is_array($items)) {
            return [$items];
        }

        return array_reduce($items, function ($carry, $item) use ($deep) {
            return array_merge($carry, $deep ? self::arrayFlatten($item) : (is_array($item) ? $item : [$item]));
        }, []);
    }

    /**
     * Add paramters "key" => "value" to the URL
     * Implementation based on that from https://stackoverflow.com/a/5809881
     */
    public static function addQueryArgs(array $keyValues, string $urlOrURLPath): string
    {
        if (!$keyValues) {
            return $urlOrURLPath;
        }

        $url_parts = parse_url($urlOrURLPath);
        if (isset($url_parts['query'])) {
            parse_str($url_parts['query'], $params);
        } else {
            $params = array();
        }

        $params = array_merge(
            $params,
            $keyValues
        );

        // Note that this will url_encode all values
        $url_parts['query'] = http_build_query($params);
        // Check if schema/host are present, becase the URL can also be a relative path: /some-path/
        $port = isset($url_parts['port']) && $url_parts['port'] ? (($url_parts['port'] == "80") ? "" : (":" . $url_parts['port'])) : '';
        $scheme = isset($url_parts['scheme']) ? $url_parts['scheme'] . '://' : '';
        return $scheme . ($url_parts['host'] ?? '') . $port . $url_parts['path'] . '?' . $url_parts['query'];
    }

    /**
     * Add paramters "key" => "value" to the URL
     * Implementation based on that from https://stackoverflow.com/a/5809881
     */
    public static function removeQueryArgs(array $keys, string $urlOrURLPath): string
    {
        if (!$keys) {
            return $urlOrURLPath;
        }

        $url_parts = parse_url($urlOrURLPath);
        if (isset($url_parts['query'])) {
            parse_str($url_parts['query'], $params);
        } else {
            $params = array();
        }

        // Remove the indicated keys
        $params = array_filter(
            $params,
            function ($param) use ($keys) {
                return in_array($param, $keys);
            },
            ARRAY_FILTER_USE_KEY
        );

        $scheme = $url_parts['scheme'] ?? '';
        // Note that this will url_encode all values
        $url_parts['query'] = http_build_query($params);
        $port = $url_parts['port'] ?? '';
        $port = (!$port || $port == '80' || ($scheme == 'https' && $port == '443')) ? '' : (':' . $port);
        $query = $url_parts['query'] ?? '';
        $scheme .= $scheme ? '://' : '';
        return $scheme . ($url_parts['host'] ?? '') . $port . $url_parts['path'] . ($query ? '?' . $query : '');
    }

    public static function maybeAddTrailingSlash(string $text): string
    {
        return rtrim($text, '/\\') . '/';
    }

    public static function getDomain(string $url): string
    {
        $parse = parse_url($url);
        return $parse['scheme'] . '://' . $parse['host'];
    }

    public static function removeDomain(string $url): string
    {
        return substr($url, strlen(self::getDomain($url)));
    }

    public static function getPath(string $url): string
    {
        $parse = parse_url($url);
        return $parse['path'];
    }

    /**
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
}
