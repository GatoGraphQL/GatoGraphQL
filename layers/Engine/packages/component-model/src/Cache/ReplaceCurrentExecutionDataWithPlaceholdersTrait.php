<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Cache;

use PoP\ComponentModel\Constants\CachePlaceholders;

trait ReplaceCurrentExecutionDataWithPlaceholdersTrait
{
    protected function getCacheReplacements()
    {
        return [
            POP_CONSTANT_UNIQUE_ID => CachePlaceholders::UNIQUE_ID,
            POP_CONSTANT_RAND => CachePlaceholders::RAND,
            POP_CONSTANT_TIME => CachePlaceholders::TIME,
        ];
    }

    protected function replaceCurrentExecutionDataWithPlaceholders($content)
    {
        $replacements = $this->getCacheReplacements();
        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $content
        );
    }

    protected function replacePlaceholdersWithCurrentExecutionData($content)
    {
        /**
         * Content may be null if it had not been cached
         */
        if (is_null($content)) {
            return null;
        }
        // Replace the placeholder for the uniqueId with the current uniqueId
        // Do the same with all dynamic constants, so that we can generate a proper ETag also when retrieving the cached value
        $replacements = $this->getCacheReplacements();
        $replaceFrom = array_values($replacements);
        $replaceTo = array_keys($replacements);
        if (is_array($content)) {
            /**
             * A faster way to replace the strings in multidimensional array
             * is to json_encode() it, do the str_replace() and then json_decode() it
             * @see https://www.php.net/manual/en/function.str-replace.php#100871
             */
            return json_decode(
                str_replace(
                    $replaceFrom,
                    $replaceTo,
                    json_encode($content)
                ),
                true
            );
        }
        return str_replace(
            $replaceFrom,
            $replaceTo,
            $content
        );
    }
}
