<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\MutationResolvers;

use PoPSchema\SchemaCommons\Enums\OperationStatusEnum;
use PoPSchema\SchemaCommons\Exception\AbstractPayloadClientException;
use PoPSchema\SchemaCommons\ObjectModels\ErrorPayloadInterface;
use PoPSchema\SchemaCommons\ObjectModels\GenericErrorPayload;
use PoPSchema\SchemaCommons\ObjectModels\ObjectMutationPayload;
use PoP\ComponentModel\Container\ObjectDictionaryInterface;

trait PayloadableMutationResolverTrait
{
    abstract protected function getObjectDictionary(): ObjectDictionaryInterface;

    protected function createAndStoreSuccessObjectMutationPayload(
        string|int $objectID,
    ): ObjectMutationPayload {
        $payload = new ObjectMutationPayload(
            OperationStatusEnum::SUCCESS,
            $objectID,
            null,
        );
        $this->getObjectDictionary()->set(
            ObjectMutationPayload::class,
            $payload->getID(),
            $payload,
        );
        return $payload;
    }

    /**
     * @param ErrorPayloadInterface[] $errors
     */
    protected function createAndStoreFailureObjectMutationPayload(
        array $errors,
        string|int|null $objectID = null,
    ): ObjectMutationPayload {
        $payload = new ObjectMutationPayload(
            OperationStatusEnum::FAILURE,
            $objectID,
            $errors,
        );
        $this->getObjectDictionary()->set(
            ObjectMutationPayload::class,
            $payload->getID(),
            $payload,
        );
        return $payload;
    }

    protected function createAndStoreGenericErrorPayloadFromPayloadClientException(
        AbstractPayloadClientException $payloadClientException
    ): GenericErrorPayload {
        $errorCode = $payloadClientException->getErrorCode();
        if ($errorCode !== null) {
            $errorCode = (string) $payloadClientException->getErrorCode();
        }
        $errorPayload = new GenericErrorPayload(
            $payloadClientException->getMessage(),
            $errorCode,
            $payloadClientException->getData(),
        );
        $this->getObjectDictionary()->set(
            get_class($errorPayload),
            $errorPayload->getID(),
            $errorPayload,
        );
        return $errorPayload;
    }
}
