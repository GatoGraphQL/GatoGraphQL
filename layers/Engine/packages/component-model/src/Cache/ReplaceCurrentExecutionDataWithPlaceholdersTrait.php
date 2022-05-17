<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Cache;

use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleInfo;
use PoP\ComponentModel\Constants\CachePlaceholders;
use PoP\Root\App;

trait ReplaceCurrentExecutionDataWithPlaceholdersTrait
{
    /**
     * @return array<int|string,int|string>
     */
    protected function getCacheReplacements(): array
    {
        /** @var ModuleInfo */
        $componentInfo = App::getComponent(Module::class)->getInfo();
        return [
            $componentInfo->getUniqueID() => CachePlaceholders::UNIQUE_ID,
            $componentInfo->getRand() => CachePlaceholders::RAND,
            $componentInfo->getTime() => CachePlaceholders::TIME,
        ];
    }

    protected function replaceCurrentExecutionDataWithPlaceholders(string $content): string
    {
        $replacements = $this->getCacheReplacements();
        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $content
        );
    }

    protected function replacePlaceholdersWithCurrentExecutionData(string|array|null $content): string|array|null
    {
        /**
         * Content may be null if it had not been cached
         */
        if ($content === null) {
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
