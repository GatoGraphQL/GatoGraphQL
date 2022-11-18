<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\MutationResolvers;

use PoPSchema\SchemaCommons\Enums\OperationStatusEnum;
use PoPSchema\SchemaCommons\Exception\AbstractPayloadClientException;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayload;
use PoPSchema\SchemaCommons\ObjectModels\MutationPayload;
use PoP\ComponentModel\Container\ObjectDictionaryInterface;

trait PayloadableMutationResolverTrait
{
    abstract protected function getObjectDictionary(): ObjectDictionaryInterface;

    public function createSuccessPayloadMutation(string|int $objectID): string|int
    {
        $payload = new MutationPayload(
            OperationStatusEnum::SUCCESS,
            $objectID,
            null,
        );
        $this->getObjectDictionary()->set(
            MutationPayload::class,
            $payload->getID(),
            $payload,
        );
        return $payload->getID();
    }

    public function createFailurePayloadMutation(AbstractPayloadClientException $payloadClientException): string|int
    {
        $payload = new MutationPayload(
            OperationStatusEnum::FAILURE,
            null,
            [
                new ErrorPayload(
                    $payloadClientException->getMessage(),
                    $payloadClientException->getErrorCode(),
                    $payloadClientException->getData(),
                ),
            ],
        );
        $this->getObjectDictionary()->set(
            MutationPayload::class,
            $payload->getID(),
            $payload,
        );
        return $payload->getID();
    }
}
