<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\MutationResolvers;

use PoPSchema\SchemaCommons\Enums\OperationStatusEnum;
use PoPSchema\SchemaCommons\ObjectModels\MutationPayload;
use PoP\ComponentModel\Container\ObjectDictionaryInterface;

trait PayloadableMutationResolverTrait
{
    abstract protected function getObjectDictionary(): ObjectDictionaryInterface;

    protected function createAndStoreSuccessPayloadMutation(
        string|int $objectID,
    ): MutationPayload {
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
        return $payload;
    }

    /**
     * @param ErrorPayloadInterface[] $errors
     */
    protected function createAndStoreFailurePayloadMutation(
        array $errors,
        string|int|null $objectID = null,
    ): MutationPayload {
        $payload = new MutationPayload(
            OperationStatusEnum::FAILURE,
            $objectID,
            $errors,
        );
        $this->getObjectDictionary()->set(
            MutationPayload::class,
            $payload->getID(),
            $payload,
        );
        return $payload;
    }
}
