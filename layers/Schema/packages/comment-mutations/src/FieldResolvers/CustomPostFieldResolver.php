<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\Comments\TypeResolvers\Object\CommentTypeResolver;
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

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'addComment' => $this->translationAPI->__('Add a comment to the custom post', 'comment-mutations'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'addComment':
                return SchemaDefinitionHelpers::getAddCommentToCustomPostSchemaFieldArgs($relationalTypeResolver, $fieldName, false, true);
        }
        return parent::getSchemaFieldArgs($relationalTypeResolver, $fieldName);
    }

    /**
     * Validated the mutation on the resultItem because the ID
     * is obtained from the same object, so it's not originally
     * present in $form_data
     */
    public function validateMutationOnResultItem(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldName
    ): bool {
        switch ($fieldName) {
            case 'addComment':
                return true;
        }
        return parent::validateMutationOnResultItem($relationalTypeResolver, $fieldName);
    }

    protected function getFieldArgsToExecuteMutation(
        array $fieldArgs,
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $resultItem,
        string $fieldName
    ): array {
        $fieldArgs = parent::getFieldArgsToExecuteMutation(
            $fieldArgs,
            $relationalTypeResolver,
            $resultItem,
            $fieldName
        );
        $customPost = $resultItem;
        switch ($fieldName) {
            case 'addComment':
                $fieldArgs[MutationInputProperties::CUSTOMPOST_ID] = $relationalTypeResolver->getID($customPost);
                break;
        }

        return $fieldArgs;
    }

    public function resolveFieldMutationResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'addComment':
                return AddCommentToCustomPostMutationResolver::class;
        }

        return parent::resolveFieldMutationResolverClass($relationalTypeResolver, $fieldName);
    }

    public function getFieldTypeResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'addComment':
                return CommentTypeResolver::class;
        }

        return parent::getFieldTypeResolverClass($relationalTypeResolver, $fieldName);
    }
}
