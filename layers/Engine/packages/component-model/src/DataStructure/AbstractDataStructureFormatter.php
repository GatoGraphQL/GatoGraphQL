<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DataStructure;

use PoP\ComponentModel\Schema\FeedbackMessageStoreInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Services\BasicServiceTrait;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractDataStructureFormatter implements DataStructureFormatterInterface
{
    use BasicServiceTrait;
    
    private ?FeedbackMessageStoreInterface $feedbackMessageStore = null;
    private ?FieldQueryInterpreterInterface $fieldQueryInterpreter = null;

    public function setFeedbackMessageStore(FeedbackMessageStoreInterface $feedbackMessageStore): void
    {
        $this->feedbackMessageStore = $feedbackMessageStore;
    }
    protected function getFeedbackMessageStore(): FeedbackMessageStoreInterface
    {
        return $this->feedbackMessageStore ??= $this->instanceManager->getInstance(FeedbackMessageStoreInterface::class);
    }
    public function setFieldQueryInterpreter(FieldQueryInterpreterInterface $fieldQueryInterpreter): void
    {
        $this->fieldQueryInterpreter = $fieldQueryInterpreter;
    }
    protected function getFieldQueryInterpreter(): FieldQueryInterpreterInterface
    {
        return $this->fieldQueryInterpreter ??= $this->instanceManager->getInstance(FieldQueryInterpreterInterface::class);
    }

    //#[Required]
    final public function autowireAbstractDataStructureFormatter(FeedbackMessageStoreInterface $feedbackMessageStore, FieldQueryInterpreterInterface $fieldQueryInterpreter): void
    {
        $this->feedbackMessageStore = $feedbackMessageStore;
        $this->fieldQueryInterpreter = $fieldQueryInterpreter;
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
