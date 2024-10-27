<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMutations\TypeResolvers\ObjectType\AbstractCommentMutationPayloadObjectTypeResolver;
use PoPCMSSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractObjectMutationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class CommentMutationPayloadObjectTypeFieldResolver extends AbstractObjectMutationPayloadObjectTypeFieldResolver
{
    private ?CommentObjectTypeResolver $commentObjectTypeResolver = null;

    final protected function getCommentObjectTypeResolver(): CommentObjectTypeResolver
    {
        if ($this->commentObjectTypeResolver === null) {
            /** @var CommentObjectTypeResolver */
            $commentObjectTypeResolver = $this->instanceManager->getInstance(CommentObjectTypeResolver::class);
            $this->commentObjectTypeResolver = $commentObjectTypeResolver;
        }
        return $this->commentObjectTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCommentMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getObjectFieldName(): string
    {
        return 'comment';
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            $this->getObjectFieldName() => $this->getCommentObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }
}
