<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructure;

use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\BasicService\BasicServiceTrait;

abstract class AbstractDataStructureFormatter implements DataStructureFormatterInterface
{
    use BasicServiceTrait;

    private ?FeedbackMessageStoreInterface $feedbackMessageStore = null;
    private ?FieldQueryInterpreterInterface $fieldQueryInterpreter = null;

    final public function setFeedbackMessageStore(FeedbackMessageStoreInterface $feedbackMessageStore): void
    {
        $this->feedbackMessageStore = $feedbackMessageStore;
    }
    final protected function getFeedbackMessageStore(): FeedbackMessageStoreInterface
    {
        return $this->feedbackMessageStore ??= $this->instanceManager->getInstance(FeedbackMessageStoreInterface::class);
    }
    final public function setFieldQueryInterpreter(FieldQueryInterpreterInterface $fieldQueryInterpreter): void
    {
        $this->fieldQueryInterpreter = $fieldQueryInterpreter;
    }
    final protected function getFieldQueryInterpreter(): FieldQueryInterpreterInterface
    {
        return $this->fieldQueryInterpreter ??= $this->instanceManager->getInstance(FieldQueryInterpreterInterface::class);
    }

    public function getFormattedData($data)
    {
        return $data;
    }

    public function outputResponse(&$data, array $headers = []): void
    {
        $this->sendHeaders($headers);
        $this->printData($data);
    }

    protected function sendHeaders(array $headers = []): void
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
