<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructure;

trait PropertyDataStructureFormatterTrait
{
    public function getContentType()
    {
        return 'text/plain';
    }

    /**
     * Iterate the array and print all the entries as a properties file
     */
    protected function printData(array &$data): void
    {
        $this->iterativelyPrintDataEntries($data, '');
    }

    /**
     * Iterate all the way down the data entries until it's not an array anymore, and then print the entry in a `property=value` format
     */
    protected function iterativelyPrintDataEntries(array|string &$data, string $property): void
    {
        if (is_array($data)) {
            foreach ($data as $key => &$value) {
                if ($property) {
                    // For 1-dimension arrays, spread the array as "property[index]"
                    if (is_int($key)) {
                        $nextLevelProperty = sprintf(
                            '%s[%s]',
                            $property,
                            $key
                        );
                    } else {
                        // For 2-dimension arrays, spread the array as "property.subproperty"
                        $nextLevelProperty = sprintf(
                            '%s.%s',
                            $property,
                            $key
                        );
                    }
                } else {
                    $nextLevelProperty = $key;
                }
                $this->iterativelyPrintDataEntries($value, $nextLevelProperty);
            }
        } else {
            $this->printDataEntry($property, (string) $data);
        }
    }

    protected function printDataEntry(string $property, string $value)
    {
        echo $property . '=' . $value . PHP_EOL;
    }
}
