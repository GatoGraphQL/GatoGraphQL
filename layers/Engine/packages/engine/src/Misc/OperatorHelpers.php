<?php

declare(strict_types=1);

namespace PoP\Engine\Misc;

use Exception;
use PoP\Engine\Constants\OperationSymbols;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

class OperatorHelpers
{
    protected static function throwNoArrayItemUnderPathException(array $data, string $path): void
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        throw new Exception(sprintf(
            $translationAPI->__('Path \'%s\' is not reachable for object: %s', 'pop-component-model'),
            $path,
            json_encode($data)
        ));
    }
    public static function &getPointerToArrayItemUnderPath(array &$data, string $path): array
    {
        $dataPointer = &$data;

        // Iterate the data array to the provided path.
        foreach (explode(OperationSymbols::ARRAY_PATH_DELIMITER, $path) as $pathLevel) {
            if (!$dataPointer) {
                // If we reached the end of the array and can't keep going down any level more, then it's an error
                self::throwNoArrayItemUnderPathException($data, $path);
            }
            if (array_key_exists($pathLevel, $dataPointer)) {
                // Retrieve the property under the pathLevel
                $dataPointer = &$dataPointer[$pathLevel];
                continue;
            }
            if (is_array($dataPointer) && isset($dataPointer[0]) && is_array($dataPointer[0]) && isset($dataPointer[0][$pathLevel])) {
                // If it is an array, then retrieve that property from each element of the array
                $dataPointerArray = array_map(function ($item) use ($pathLevel) {
                    return $item[$pathLevel];
                }, $dataPointer);
                $dataPointer = &$dataPointerArray;
                continue;
            }
            // We are accessing a level that doesn't exist
            // If we reached the end of the array and can't keep going down any level more, then it's an error
            self::throwNoArrayItemUnderPathException($data, $path);
        }
        return $dataPointer;
    }
    public static function setValueToArrayItemUnderPath(array &$data, string $path, $value): void
    {
        $dataPointer = &$data;

        // Iterate the data array to the provided path.
        foreach (explode(OperationSymbols::ARRAY_PATH_DELIMITER, $path) as $pathLevel) {
            if (!isset($dataPointer[$pathLevel])) {
                // If we reached the end of the array and can't keep going down any level more, then it's an error
                self::throwNoArrayItemUnderPathException($data, $path);
            }
            $dataPointer = &$dataPointer[$pathLevel];
        }
        // We reached the end. Set the value
        $dataPointer = $value;
    }
}
