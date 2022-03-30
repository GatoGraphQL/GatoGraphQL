<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ItemProcessors;

use PoP\Definitions\Facades\DefinitionManagerFacade;

class ProcessorItemUtils
{
    public static function getItemFullName(array $item): string
    {
        // Expand the item's properties:
        // $item[0]: class
        // $item[1]: name
        // $item[2]: extra atts (optional)
        $itemFullNameElems = [$item[0], $item[1]];
        $itemAtts = $item[2] ?? null;
        if ($itemAtts !== null) {
            $itemAtts[] = serialize($itemAtts);
        }
        return implode(Constants::SEPARATOR_PROCESSORITEMFULLNAME, $itemFullNameElems);
    }
    public static function getItemFromFullName(string $itemFullName): ?array
    {
        $parts = explode(Constants::SEPARATOR_PROCESSORITEMFULLNAME, $itemFullName);
        // There must be at least 2 parts: class and name
        if (count($parts) < 2) {
            return null;
        }

        // Does it have itematts? If so, unserialize them.
        return (count($parts) >= 3) ?
            [
                $parts[0], // class
                $parts[1], // name
                unserialize(
                    // Just in case itematts contains the same SEPARATOR_PROCESSORITEMFULLNAME string inside of it, simply recalculate the whole itematts from the $itemFullName string
                    substr(
                        $itemFullName,
                        strlen(
                            $parts[0] . Constants::SEPARATOR_PROCESSORITEMFULLNAME . $parts[1] . Constants::SEPARATOR_PROCESSORITEMFULLNAME
                        )
                    )
                )
            ] :
            // Otherwise, the parts are already the item
            $parts;
    }
    public static function getItemOutputName(array $item, string $definitionGroup): string
    {
        return DefinitionManagerFacade::getInstance()->getDefinition(self::getItemFullName($item), $definitionGroup);
    }
    public static function getItemFromOutputName(string $itemOutputName, string $definitionGroup): ?array
    {
        return self::getItemFromFullName(DefinitionManagerFacade::getInstance()->getOriginalName($itemOutputName, $definitionGroup));
    }
}
