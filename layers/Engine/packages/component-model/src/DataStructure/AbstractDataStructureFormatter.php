<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructure;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;

abstract class AbstractDataStructureFormatter implements DataStructureFormatterInterface
{
    protected FeedbackMessageStoreInterface $feedbackMessageStore;
    protected FieldQueryInterpreterInterface $fieldQueryInterpreter;

    #[Required]
    public function autowireAbstractDataStructureFormatter(FeedbackMessageStoreInterface $feedbackMessageStore, FieldQueryInterpreterInterface $fieldQueryInterpreter)
    {
        $this->feedbackMessageStore = $feedbackMessageStore;
        $this->fieldQueryInterpreter = $fieldQueryInterpreter;
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
