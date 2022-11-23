<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\MutationResolvers;

use PoPSchema\SchemaCommons\Enums\OperationStatusEnum;
use PoPSchema\SchemaCommons\Exception\AbstractPayloadClientException;
use PoPSchema\SchemaCommons\ObjectModels\GenericErrorPayload;
use PoPSchema\SchemaCommons\ObjectModels\MutationPayload;
use PoP\ComponentModel\Container\ObjectDictionaryInterface;

trait PayloadableMutationResolverTrait
{
    abstract protected function getObjectDictionary(): ObjectDictionaryInterface;

    protected function createSuccessPayloadMutation(string|int $objectID): string|int
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

    protected function createFailurePayloadMutation(AbstractPayloadClientException $payloadClientException): string|int
    {
        $payload = new MutationPayload(
            OperationStatusEnum::FAILURE,
            null,
            [
                $this->createGenericErrorPayload($payloadClientException),
            ],
        );
        $this->getObjectDictionary()->set(
            MutationPayload::class,
            $payload->getID(),
            $payload,
        );
        return $payload->getID();
    }

    protected function createGenericErrorPayload(AbstractPayloadClientException $payloadClientException): GenericErrorPayload
    {
        return new GenericErrorPayload(
            $payloadClientException->getMessage(),
            $payloadClientException->getErrorCode(),
            $payloadClientException->getData(),
        );
    }
}
