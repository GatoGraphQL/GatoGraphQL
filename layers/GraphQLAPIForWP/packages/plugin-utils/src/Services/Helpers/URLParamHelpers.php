<?php

declare(strict_types=1);

namespace GraphQLAPI\PluginUtils\Services\Helpers;

class URLParamHelpers
{
    /**
     * Reproduce exactly the `encodeURIComponent` JavaScript function.
     * Given the endpoint URL `$url`, add the query and variable params like this:
     * $url .= '?query=' . URLParamHelpers::encodeURIComponent($query);
     * // Add variables parameter always (empty if no variables defined), so that GraphiQL doesn't use a cached one
     * $url .= '&variables=' . ($variables ? URLParamHelpers::encodeURIComponent($variables) : '');
     * Taken from https://stackoverflow.com/a/1734255
     */
    public function encodeURIComponent(string $str): string
    {
        $revert = array('%21' => '!', '%2A' => '*', '%27' => "'", '%28' => '(', '%29' => ')');
        return \strtr(\rawurlencode($str), $revert);
    }
}
