<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructure;

use SimpleXMLElement;

trait XMLDataStructureFormatterTrait
{
    public function getContentType()
    {
        return 'text/xml';
    }

    /**
     * Iterate the array and print all the entries as a properties file
     *
     * @param [type] $data
     * @return void
     */
    protected function printData(&$data)
    {
        // Code taken from Function taken from https://stackoverflow.com/a/5965940
        $xml_data = new SimpleXMLElement('<?xml version="1.0"?><data></data>');
        $this->arrayToXML($data, $xml_data);
        echo $xml_data->asXML();
    }

    /**
     * Fill an xml element with the contents from the array
     * Taken from https://stackoverflow.com/a/5965940
     *
     * @param SimpleXMLElement $object
     * @param array $data
     * @return void
     */
    protected function arrayToXML($data, &$xml_data)
    {
        foreach ($data as $key => $value) {
            if (is_numeric($key)) {
                $key = 'item' . $key; //dealing with <0/>..<n/> issues
            }
            if (is_array($value)) {
                $subnode = $xml_data->addChild($key);
                $this->arrayToXML($value, $subnode);
            } else {
                $xml_data->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }
}
