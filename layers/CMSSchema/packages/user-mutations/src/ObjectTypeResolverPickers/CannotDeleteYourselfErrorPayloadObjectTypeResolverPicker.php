<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\UserMutations\ObjectModels\CannotDeleteYourselfErrorPayload;
use PoPCMSSchema\UserMutations\TypeResolvers\ObjectType\CannotDeleteYourselfErrorPayloadObjectTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\AbstractDeleteUserMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class CannotDeleteYourselfErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?CannotDeleteYourselfErrorPayloadObjectTypeResolver $cannotDeleteYourselfErrorPayloadObjectTypeResolver = null;

    final protected function getCannotDeleteYourselfErrorPayloadObjectTypeResolver(): CannotDeleteYourselfErrorPayloadObjectTypeResolver
    {
        if ($this->cannotDeleteYourselfErrorPayloadObjectTypeResolver === null) {
            /** @var CannotDeleteYourselfErrorPayloadObjectTypeResolver */
            $cannotDeleteYourselfErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(CannotDeleteYourselfErrorPayloadObjectTypeResolver::class);
            $this->cannotDeleteYourselfErrorPayloadObjectTypeResolver = $cannotDeleteYourselfErrorPayloadObjectTypeResolver;
        }
        return $this->cannotDeleteYourselfErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getCannotDeleteYourselfErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return CannotDeleteYourselfErrorPayload::class;
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
