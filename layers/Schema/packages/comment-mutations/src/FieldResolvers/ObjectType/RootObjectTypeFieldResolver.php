<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\FieldResolvers\ObjectType;

use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoPSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\CommentMutations\Schema\SchemaDefinitionHelpers;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\Engine\ComponentConfiguration as EngineComponentConfiguration;
use PoPSchema\CommentMutations\MutationResolvers\AddCommentToCustomPostMutationResolver;

class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        if (EngineComponentConfiguration::disableRedundantRootTypeMutationFields()) {
            return [];
        }
        return [
            'addCommentToCustomPost',
            'replyComment',
        ];
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'addCommentToCustomPost' => $this->translationAPI->__('Add a comment to a custom post', 'comment-mutations'),
            'replyComment' => $this->translationAPI->__('Reply a comment with another comment', 'comment-mutations'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'addCommentToCustomPost':
                return SchemaDefinitionHelpers::getAddCommentToCustomPostSchemaFieldArgs($objectTypeResolver, $fieldName, true, true);
            case 'replyComment':
                return SchemaDefinitionHelpers::getAddCommentToCustomPostSchemaFieldArgs($objectTypeResolver, $fieldName, false, true, true);
        }
        return parent::getSchemaFieldArgs($objectTypeResolver, $fieldName);
    }

    public function getFieldMutationResolverClass(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'addCommentToCustomPost':
            case 'replyComment':
                return AddCommentToCustomPostMutationResolver::class;
        }

        return parent::getFieldMutationResolverClass($objectTypeResolver, $fieldName);
    }

    public function getFieldTypeResolverClass(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string
    {
        switch ($fieldName) {
            case 'addCommentToCustomPost':
            case 'replyComment':
                return CommentObjectTypeResolver::class;
        }

        return parent::getFieldTypeResolverClass($objectTypeResolver, $fieldName);
    }
}
