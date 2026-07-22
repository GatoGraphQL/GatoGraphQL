<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\UserMutations\ObjectModels\ReassignUserDoesNotExistErrorPayload;
use PoPCMSSchema\UserMutations\TypeResolvers\ObjectType\ReassignUserDoesNotExistErrorPayloadObjectTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\AbstractDeleteUserMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class ReassignUserDoesNotExistErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?ReassignUserDoesNotExistErrorPayloadObjectTypeResolver $reassignUserDoesNotExistErrorPayloadObjectTypeResolver = null;

    final protected function getReassignUserDoesNotExistErrorPayloadObjectTypeResolver(): ReassignUserDoesNotExistErrorPayloadObjectTypeResolver
    {
        if ($this->reassignUserDoesNotExistErrorPayloadObjectTypeResolver === null) {
            /** @var ReassignUserDoesNotExistErrorPayloadObjectTypeResolver */
            $reassignUserDoesNotExistErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(ReassignUserDoesNotExistErrorPayloadObjectTypeResolver::class);
            $this->reassignUserDoesNotExistErrorPayloadObjectTypeResolver = $reassignUserDoesNotExistErrorPayloadObjectTypeResolver;
        }
        return $this->reassignUserDoesNotExistErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getReassignUserDoesNotExistErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return ReassignUserDoesNotExistErrorPayload::class;
    }

    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractDeleteUserMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
