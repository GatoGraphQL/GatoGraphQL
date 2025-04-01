<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMetaMutations\FieldResolvers\ObjectType;

use PoPCMSSchema\CommentMetaMutations\TypeResolvers\ObjectType\CommentSetMetaMutationPayloadObjectTypeResolver;
use PoPCMSSchema\CommentMetaMutations\TypeResolvers\UnionType\CommentSetMetaMutationErrorPayloadUnionTypeResolver;
use PoPSchema\SchemaCommons\FieldResolvers\ObjectType\AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class CommentSetMetaMutationPayloadErrorsFieldTransientOperationPayloadObjectTypeFieldResolver extends AbstractErrorsFieldTransientOperationPayloadObjectTypeFieldResolver
{
    private ?CommentSetMetaMutationErrorPayloadUnionTypeResolver $commentSetMetaMutationErrorPayloadUnionTypeResolver = null;

    final protected function getCommentSetMetaMutationErrorPayloadUnionTypeResolver(): CommentSetMetaMutationErrorPayloadUnionTypeResolver
    {
        if ($this->commentSetMetaMutationErrorPayloadUnionTypeResolver === null) {
            /** @var CommentSetMetaMutationErrorPayloadUnionTypeResolver */
            $commentSetMetaMutationErrorPayloadUnionTypeResolver = $this->instanceManager->getInstance(CommentSetMetaMutationErrorPayloadUnionTypeResolver::class);
            $this->commentSetMetaMutationErrorPayloadUnionTypeResolver = $commentSetMetaMutationErrorPayloadUnionTypeResolver;
        }
        return $this->commentSetMetaMutationErrorPayloadUnionTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            CommentSetMetaMutationPayloadObjectTypeResolver::class,
        ];
    }

    protected function getErrorsFieldFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return $this->getCommentSetMetaMutationErrorPayloadUnionTypeResolver();
    }
}
