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
     *
     * @param [type] $data
     * @return void
     */
    protected function printData(&$data)
    {
        $this->iterativelyPrintDataEntries($data, '');
    }

    /**
     * Iterate all the way down the data entries until it's not an array anymore, and then print the entry in a `property=value` format
     *
     * @param [type] $data
     * @param string $property
     * @return void
     */
    protected function iterativelyPrintDataEntries(&$data, string $property)
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
            $this->printDataEntry($property, $data);
        }
    }

    protected function printDataEntry(string $property, $value)
    {
        echo $property . '=' . $value . PHP_EOL;
    }
}
