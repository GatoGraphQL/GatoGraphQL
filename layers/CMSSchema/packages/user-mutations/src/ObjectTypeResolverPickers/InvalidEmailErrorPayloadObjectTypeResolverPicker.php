<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMutations\ObjectTypeResolverPickers;

use PoPCMSSchema\UserMutations\ObjectModels\InvalidEmailErrorPayload;
use PoPCMSSchema\UserMutations\TypeResolvers\ObjectType\InvalidEmailErrorPayloadObjectTypeResolver;
use PoPCMSSchema\UserMutations\TypeResolvers\UnionType\AbstractCreateOrUpdateUserMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\ObjectTypeResolverPickers\AbstractErrorPayloadObjectTypeResolverPicker;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;

class InvalidEmailErrorPayloadObjectTypeResolverPicker extends AbstractErrorPayloadObjectTypeResolverPicker
{
    private ?InvalidEmailErrorPayloadObjectTypeResolver $invalidEmailErrorPayloadObjectTypeResolver = null;

    final protected function getInvalidEmailErrorPayloadObjectTypeResolver(): InvalidEmailErrorPayloadObjectTypeResolver
    {
        if ($this->invalidEmailErrorPayloadObjectTypeResolver === null) {
            /** @var InvalidEmailErrorPayloadObjectTypeResolver */
            $invalidEmailErrorPayloadObjectTypeResolver = $this->instanceManager->getInstance(InvalidEmailErrorPayloadObjectTypeResolver::class);
            $this->invalidEmailErrorPayloadObjectTypeResolver = $invalidEmailErrorPayloadObjectTypeResolver;
        }
        return $this->invalidEmailErrorPayloadObjectTypeResolver;
    }

    public function getObjectTypeResolver(): ObjectTypeResolverInterface
    {
        return $this->getInvalidEmailErrorPayloadObjectTypeResolver();
    }

    protected function getTargetObjectClass(): string
    {
        return InvalidEmailErrorPayload::class;
    }

    /**
     * @return array<class-string<UnionTypeResolverInterface>>
     */
    public function getUnionTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCreateOrUpdateUserMutationErrorPayloadUnionTypeResolver::class,
        ];
    }
}
