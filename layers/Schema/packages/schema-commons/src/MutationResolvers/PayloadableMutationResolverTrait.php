<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\MutationResolvers;

use PoPSchema\SchemaCommons\Enums\OperationStatusEnum;
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

    /**
     * @param ErrorPayloadInterface[] $errors
     */
    protected function createFailurePayloadMutation(
        array $errors,
        string|int|null $objectID = null,
    ): string|int {
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
        return $payload->getID();
    }
}
