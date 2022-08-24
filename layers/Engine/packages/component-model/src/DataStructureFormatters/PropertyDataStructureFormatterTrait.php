<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructureFormatters;

trait PropertyDataStructureFormatterTrait
{
    public function getContentType(): string
    {
        return 'text/plain';
    }

    /**
     * Iterate the array and print all the entries as a properties file
     * @param array<string,mixed> $data
     */
    public function getOutputContent(array &$data): string
    {
        $outputLines = [];
        $this->iterativelyAddOutputLines($outputLines, $data, '');
        return implode(PHP_EOL, $outputLines);
    }

    /**
     * Iterate all the way down the data entries until it's not an array anymore, and then print the entry in a `property=value` format
     * @param string[] $outputLines
     * @param string|array<string|int,mixed> $data
     */
    protected function iterativelyAddOutputLines(array &$outputLines, string|array &$data, string $property): void
    {
        if (!is_array($data)) {
            $outputLines[] = $this->getDataEntry($property, (string) $data);
            return;
        }
        foreach ($data as $key => &$value) {
            if ($property !== '' && is_int($key)) {
                // For 1-dimension arrays, spread the array as "property[index]"
                $nextLevelProperty = sprintf(
                    '%s[%s]',
                    $property,
                    $key
                );
            } elseif ($property !== '') {
                // For 2-dimension arrays, spread the array as "property.subproperty"
                $nextLevelProperty = sprintf(
                    '%s.%s',
                    $property,
                    $key
                );
            } else {
                $nextLevelProperty = (string)$key;
            }
            $this->iterativelyAddOutputLines($outputLines, $value, $nextLevelProperty);
        }
    }

    protected function getDataEntry(string $property, string $value): string
    {
        return $property . '=' . $value;
    }
}
