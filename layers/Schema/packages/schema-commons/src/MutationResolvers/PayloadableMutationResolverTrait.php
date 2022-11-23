<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\MutationResolvers;

use PoPSchema\SchemaCommons\Enums\OperationStatusEnum;
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
}
