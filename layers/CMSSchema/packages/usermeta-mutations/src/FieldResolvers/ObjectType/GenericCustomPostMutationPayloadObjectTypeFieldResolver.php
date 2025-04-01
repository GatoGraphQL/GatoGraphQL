<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\Users\TypeResolvers\ObjectType\GenericUserObjectTypeResolver;
use PoPCMSSchema\UserMetaMutations\TypeResolvers\ObjectType\AbstractGenericUserMetaMutationPayloadObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractObjectMutationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class GenericUserMutationPayloadObjectTypeFieldResolver extends AbstractObjectMutationPayloadObjectTypeFieldResolver
{
    private ?GenericUserObjectTypeResolver $genericUserObjectTypeResolver = null;

    final protected function getGenericUserObjectTypeResolver(): GenericUserObjectTypeResolver
    {
        if ($this->genericUserObjectTypeResolver === null) {
            /** @var GenericUserObjectTypeResolver */
            $genericUserObjectTypeResolver = $this->instanceManager->getInstance(GenericUserObjectTypeResolver::class);
            $this->genericUserObjectTypeResolver = $genericUserObjectTypeResolver;
        }
        return $this->genericUserObjectTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractGenericUserMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getObjectFieldName(): string
    {
        return 'user';
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            $this->getObjectFieldName() => $this->getGenericUserObjectTypeResolver(),
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
