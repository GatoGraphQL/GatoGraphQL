<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructure;

use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;

abstract class AbstractDataStructureFormatter implements DataStructureFormatterInterface
{
    public function __construct(
        protected FeedbackMessageStoreInterface $feedbackMessageStore,
        protected FieldQueryInterpreterInterface $fieldQueryInterpreter,
    ) {
    }

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

    abstract protected function printData(array &$data): void;
}
