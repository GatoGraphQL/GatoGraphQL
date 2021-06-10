<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\Comments\TypeResolvers\CommentTypeResolver;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoPSchema\CommentMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver;
use PoPSchema\CommentMutations\MutationResolvers\AddCommentToCustomPostMutationResolver;
use PoPSchema\CommentMutations\Schema\SchemaDefinitionHelpers;

class CustomPostFieldResolver extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(IsCustomPostFieldInterfaceResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'addComment',
        ];
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'addComment' => $this->translationAPI->__('Add a comment to the custom post', 'comment-mutations'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'addComment' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'addComment':
                return SchemaDefinitionHelpers::getAddCommentToCustomPostSchemaFieldArgs($typeResolver, $fieldName, false, true);
        }
        return parent::getSchemaFieldArgs($typeResolver, $fieldName);
    }

    /**
     * Validated the mutation on the resultItem because the ID
     * is obtained from the same object, so it's not originally
     * present in $form_data
     */
    public function validateMutationOnResultItem(
        TypeResolverInterface $typeResolver,
        string $fieldName
    ): bool {
        switch ($fieldName) {
            case 'addComment':
                return true;
        }
        return parent::validateMutationOnResultItem($typeResolver, $fieldName);
    }

    protected function getFieldArgsToExecuteMutation(
        array $fieldArgs,
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName
    ): array {
        $fieldArgs = parent::getFieldArgsToExecuteMutation(
            $fieldArgs,
            $typeResolver,
            $resultItem,
            $fieldName
        );
        $customPost = $resultItem;
        switch ($fieldName) {
            case 'addComment':
                $fieldArgs[MutationInputProperties::CUSTOMPOST_ID] = $typeResolver->getID($customPost);
                break;
        }

        return $fieldArgs;
    }

    public function resolveFieldMutationResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'addComment':
                return AddCommentToCustomPostMutationResolver::class;
        }

        return parent::resolveFieldMutationResolverClass($typeResolver, $fieldName);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'addComment':
                return CommentTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
