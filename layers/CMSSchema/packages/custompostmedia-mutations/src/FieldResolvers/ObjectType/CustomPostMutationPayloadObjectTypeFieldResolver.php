<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CustomPostMediaMutations\TypeResolvers\ObjectType\AbstractCustomPostMediaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractObjectMutationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class CustomPostMutationPayloadObjectTypeFieldResolver extends AbstractObjectMutationPayloadObjectTypeFieldResolver
{
    private ?CustomPostUnionTypeResolver $customPostUnionTypeResolver = null;

    final protected function getCustomPostUnionTypeResolver(): CustomPostUnionTypeResolver
    {
        if ($this->customPostUnionTypeResolver === null) {
            /** @var CustomPostUnionTypeResolver */
            $customPostUnionTypeResolver = $this->instanceManager->getInstance(CustomPostUnionTypeResolver::class);
            $this->customPostUnionTypeResolver = $customPostUnionTypeResolver;
        }
        return $this->customPostUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostMediaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getObjectFieldName(): string
    {
        return 'customPost';
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            $this->getObjectFieldName() => $this->getCustomPostUnionTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
