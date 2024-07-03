<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\FieldResolvers\ObjectType;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use stdClass;

trait MutationPayloadObjectsObjectTypeFieldResolverTrait
{
    /**
     * Simply return the same IDs from the filter
     *
     * @return array<string|int>
     */
    protected function resolveMutationPayloadObjectsValue(
        FieldDataAccessorInterface $fieldDataAccessor,
    ): array {
        /** @var stdClass */
        $mutationPayloadObjectsInput = $fieldDataAccessor->getValue('input');
        return $mutationPayloadObjectsInput->ids;
    }
}
