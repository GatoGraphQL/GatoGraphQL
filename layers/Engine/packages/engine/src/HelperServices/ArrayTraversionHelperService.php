<?php

declare(strict_types=1);

namespace PoP\Engine\HelperServices;

use PoP\Engine\Constants\OperationSymbols;
use PoP\Engine\Exception\RuntimeOperationException;
use PoP\ComponentModel\Response\OutputServiceInterface;
use PoP\Root\Services\BasicServiceTrait;

class ArrayTraversionHelperService implements ArrayTraversionHelperServiceInterface
{
    use BasicServiceTrait;

    private ?OutputServiceInterface $outputService = null;

    final public function setOutputService(OutputServiceInterface $outputService): void
    {
        $this->outputService = $outputService;
    }
    final protected function getOutputService(): OutputServiceInterface
    {
        /** @var OutputServiceInterface */
        return $this->outputService ??= $this->instanceManager->getInstance(OutputServiceInterface::class);
    }

    /**
     * @throws RuntimeOperationException If the path cannot be reached under the array, or if its value is not an array
     * @return mixed[]
     * @param array<string,mixed> $data
     */
    public function &getPointerToArrayItemUnderPath(array &$data, string $path): array
    {
        $dataPointer = $this->getPointerToElementItemUnderPath($data, $path);

        if (!is_array($dataPointer)) {
            $this->throwItemUnderPathIsNotArrayException($dataPointer, $path);
        }

        return $dataPointer;
    }

    /**
     * @throws RuntimeOperationException If the path cannot be reached under the array
     * @param array<string,mixed> $data
     */
    public function &getPointerToElementItemUnderPath(array &$data, string $path): mixed
    {
        $dataPointer = &$data;

        // Iterate the data array to the provided path.
        foreach (explode(OperationSymbols::ARRAY_PATH_DELIMITER, $path) as $pathLevel) {
            if (!$dataPointer) {
                // If we reached the end of the array and can't keep going down any level more, then it's an error
                $this->throwNoArrayItemUnderPathException($data, $path);
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
            $this->throwNoArrayItemUnderPathException($data, $path);
        }

        return $dataPointer;
    }

    /**
     * @throws RuntimeOperationException
     * @param array<string,mixed> $data
     */
    protected function throwNoArrayItemUnderPathException(array $data, string $path): void
    {
        throw new RuntimeOperationException(
            sprintf(
                $this->__('Path \'%s\' is not reachable for object: %s', 'component-model'),
                $path,
                $this->getOutputService()->jsonEncodeArrayOrStdClassValue($data)
            )
        );
    }

    /**
     * @throws RuntimeOperationException
     */
    protected function throwItemUnderPathIsNotArrayException(mixed $dataPointer, string $path): void
    {
        throw new RuntimeOperationException(
            sprintf(
                $this->__('The item under path \'%s\' (with value \'%s\') is not an array', 'component-model'),
                $path,
                $dataPointer
            )
        );
    }

    /**
     * @throws RuntimeOperationException If the path cannot be reached under the array
     * @param array<string,mixed> $data
     */
    public function setValueToArrayItemUnderPath(array &$data, string $path, mixed $value): void
    {
        $dataPointer = &$data;

        // Iterate the data array to the provided path.
        foreach (explode(OperationSymbols::ARRAY_PATH_DELIMITER, $path) as $pathLevel) {
            if (!array_key_exists($pathLevel, $dataPointer)) {
                // If we reached the end of the array and can't keep going down any level more, then it's an error
                $this->throwNoArrayItemUnderPathException($data, $path);
            }
            $dataPointer = &$dataPointer[$pathLevel];
        }

        // We reached the end. Set the value
        $dataPointer = $value;
    }
}
