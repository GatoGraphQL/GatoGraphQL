<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutations\FieldResolvers;

use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoPSchema\CustomPostMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\CustomPostMutations\Schema\SchemaDefinitionHelpers;

abstract class AbstractCustomPostFieldResolver extends AbstractDBDataFieldResolver
{
    public function getFieldNamesToResolve(): array
    {
        return [
            'update',
        ];
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'update' => $this->translationAPI->__('Update the custom post', 'custompost-mutations'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'update':
                return SchemaDefinitionHelpers::getCreateUpdateCustomPostSchemaFieldArgs(
                    $relationalTypeResolver,
                    $fieldName,
                    false,
                    $this->getFieldTypeResolverClass($relationalTypeResolver, $fieldName)
                );
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
            case 'update':
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
        $post = $resultItem;
        switch ($fieldName) {
            case 'update':
                $fieldArgs[MutationInputProperties::ID] = $relationalTypeResolver->getID($post);
                break;
        }

        return $fieldArgs;
    }
}
