<?php

declare(strict_types=1);

namespace PoPSchema\ExtendedSchemaCommons\HelperServices;

use PoP\Engine\Exception\RuntimeOperationException;
use PoP\ComponentModel\Response\OutputServiceInterface;
use PoP\Root\Services\BasicServiceTrait;
use PoPSchema\ExtendedSchemaCommons\Constants\OperationSymbols;
use stdClass;

class ArrayOrJSONObjectTraversionHelperService implements ArrayOrJSONObjectTraversionHelperServiceInterface
{
    use BasicServiceTrait;

    private ?OutputServiceInterface $outputService = null;

    final protected function getOutputService(): OutputServiceInterface
    {
        if ($this->outputService === null) {
            /** @var OutputServiceInterface */
            $outputService = $this->instanceManager->getInstance(OutputServiceInterface::class);
            $this->outputService = $outputService;
        }
        return $this->outputService;
    }

    /**
     * @throws RuntimeOperationException If the path cannot be reached under the array
     * @param array<string|int,mixed>|stdClass $data
     */
    public function &getPointerToArrayItemOrObjectPropertyUnderPath(array|stdClass &$data, int|string $path): mixed
    {
        $dataPointer = &$data;

        if (is_integer($path)) {
            if (is_array($dataPointer) && array_key_exists($path, $dataPointer)) {
                $dataPointer = &$dataPointer[$path];
            } elseif ($dataPointer instanceof stdClass && property_exists($dataPointer, (string)$path)) {
                $dataPointer = &$dataPointer->$path;
            } else {
                $this->throwNoArrayItemUnderPathException($data, $path);
            }
        } else {
            // Iterate the data array to the provided path.
            foreach (explode(OperationSymbols::ARRAY_PATH_DELIMITER, $path) as $pathLevel) {
                if (!$dataPointer) {
                    // If we reached the end of the array and can't keep going down any level more, then it's an error
                    $this->throwNoArrayItemUnderPathException($data, $path);
                }
                if (is_array($dataPointer) && array_key_exists($pathLevel, $dataPointer)) {
                    // Retrieve the property under the pathLevel
                    $dataPointer = &$dataPointer[$pathLevel];
                    continue;
                }
                if ($dataPointer instanceof stdClass && property_exists($dataPointer, $pathLevel)) {
                    // Retrieve the property under the pathLevel
                    $dataPointer = &$dataPointer->$pathLevel;
                    continue;
                }
                // We are accessing a level that doesn't exist
                // If we reached the end of the array and can't keep going down any level more, then it's an error
                $this->throwNoArrayItemUnderPathException($data, $path);
            }
        }

        return $dataPointer;
    }

    /**
     * @throws RuntimeOperationException
     * @param array<string|int,mixed>|stdClass $data
     */
    protected function throwNoArrayItemUnderPathException(array|stdClass $data, int|string $path): void
    {
        throw new RuntimeOperationException(
            is_integer($path)
                ? sprintf(
                    $this->__('Index \'%s\' is not set for array: %s', 'extended-schema-commons'),
                    $path,
                    $this->getOutputService()->jsonEncodeArrayOrStdClassValue($data)
                )
                : sprintf(
                    $this->__('Key or path \'%s\' is not reachable for object: %s', 'extended-schema-commons'),
                    $path,
                    $this->getOutputService()->jsonEncodeArrayOrStdClassValue($data)
                )
        );
    }

    /**
     * @throws RuntimeOperationException
     */
    protected function throwItemUnderPathIsNotArrayException(mixed $dataPointer, int|string $path): void
    {
        throw new RuntimeOperationException(
            is_integer($path)
                ? sprintf(
                    $this->__('The item under index \'%s\' (with value \'%s\') is not an array', 'extended-schema-commons'),
                    $path,
                    $dataPointer
                )
                : sprintf(
                    $this->__('The item under path \'%s\' (with value \'%s\') is not an array', 'extended-schema-commons'),
                    $path,
                    $dataPointer
                )
        );
    }

    /**
     * @throws RuntimeOperationException If the path cannot be reached under the array
     * @param array<string|int,mixed>|stdClass $data
     */
    public function setValueToArrayItemOrObjectPropertyUnderPath(array|stdClass &$data, int|string $path, mixed $value): void
    {
        $dataPointer = &$data;

        if (is_integer($path)) {
            if (is_array($dataPointer) && array_key_exists($path, $dataPointer)) {
                $dataPointer = &$dataPointer[$path];
            } elseif ($dataPointer instanceof stdClass && property_exists($dataPointer, (string)$path)) {
                $dataPointer = &$dataPointer->$path;
            } else {
                $this->throwNoArrayItemUnderPathException($data, $path);
            }
        } else {
            // Iterate the data array to the provided path.
            foreach (explode(OperationSymbols::ARRAY_PATH_DELIMITER, $path) as $pathLevel) {
                if (is_array($dataPointer) && array_key_exists($pathLevel, $dataPointer)) {
                    // Retrieve the property under the pathLevel
                    $dataPointer = &$dataPointer[$pathLevel];
                    continue;
                }
                if ($dataPointer instanceof stdClass && property_exists($dataPointer, $pathLevel)) {
                    // Retrieve the property under the pathLevel
                    $dataPointer = &$dataPointer->$pathLevel;
                    continue;
                }

                // If we reached the end of the array and can't keep going down any level more, then it's an error
                $this->throwNoArrayItemUnderPathException($data, $path);
            }
        }

        // We reached the end. Set the value
        $dataPointer = $value;
    }
}
