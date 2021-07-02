<?php

/**
 * Override the URLs corresponding to each domain
 */
function popMultidomainMaybeReplacepath($url, $domain, $path)
{

    // Check if this path needs be replaced
    $paths = POP_MULTIDOMAIN_EXTERNALDOMAIN_REPLACEPATHS[$domain] ?? array();
    if ($replace = $paths[$path] ?? null) {
        // Replace the path bit
        $url = str_replace($path, $replace, $url);
    }

    return $url;
}
