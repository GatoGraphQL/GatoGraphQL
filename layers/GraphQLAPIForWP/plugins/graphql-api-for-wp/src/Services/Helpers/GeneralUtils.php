<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Services\Helpers;

class GeneralUtils
{
    /**
     * Convert a string with dashes into camelCase mode
     *
     * @see https://stackoverflow.com/a/2792045
     */
    public function dashesToCamelCase(string $string, bool $capitalizeFirstCharacter = false): string
    {
        $str = str_replace('-', '', ucwords($string, '-'));
        if (!$capitalizeFirstCharacter) {
            $str = lcfirst($str);
        }
        return $str;
    }
}
