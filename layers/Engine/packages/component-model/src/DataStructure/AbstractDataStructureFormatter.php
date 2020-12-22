<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructure;

abstract class AbstractDataStructureFormatter implements DataStructureFormatterInterface
{
    public function getFormattedData($data)
    {
        return $data;
    }

    public function outputResponse(&$data, array $headers = [])
    {
        $this->sendHeaders($headers);
        $this->printData($data);
    }

    protected function sendHeaders(array $headers = [])
    {
        // Add the content type header
        if ($contentType = $this->getContentType()) {
            $headers[] = sprintf(
                'Content-type: %s',
                $contentType
            );
        }
        foreach ($headers as $header) {
            header($header);
        }
    }

    protected function printData(&$data)
    {
        echo $data;
    }
}
