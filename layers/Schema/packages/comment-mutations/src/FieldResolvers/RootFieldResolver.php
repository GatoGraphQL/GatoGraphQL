<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\FieldResolvers;

use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoPSchema\Comments\TypeResolvers\CommentTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CommentMutations\Schema\SchemaDefinitionHelpers;
use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\Engine\ComponentConfiguration as EngineComponentConfiguration;
use PoPSchema\CommentMutations\MutationResolvers\AddCommentToCustomPostMutationResolver;

class RootFieldResolver extends AbstractQueryableFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(RootTypeResolver::class);
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

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'addCommentToCustomPost' => $this->translationAPI->__('Add a comment to a custom post', 'comment-mutations'),
            'replyComment' => $this->translationAPI->__('Reply a comment with another comment', 'comment-mutations'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'addCommentToCustomPost' => SchemaDefinition::TYPE_ID,
            'replyComment' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'addCommentToCustomPost':
                return SchemaDefinitionHelpers::getAddCommentToCustomPostSchemaFieldArgs($typeResolver, $fieldName, true, true);
            case 'replyComment':
                return SchemaDefinitionHelpers::getAddCommentToCustomPostSchemaFieldArgs($typeResolver, $fieldName, false, true, true);
        }
        return parent::getSchemaFieldArgs($typeResolver, $fieldName);
    }

    public function resolveFieldMutationResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'addCommentToCustomPost':
            case 'replyComment':
                return AddCommentToCustomPostMutationResolver::class;
        }

        return parent::resolveFieldMutationResolverClass($typeResolver, $fieldName);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'addCommentToCustomPost':
            case 'replyComment':
                return CommentTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
