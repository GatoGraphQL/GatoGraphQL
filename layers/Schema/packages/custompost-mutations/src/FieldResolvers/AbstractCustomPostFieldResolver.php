<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutations\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
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

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'update' => $this->translationAPI->__('Update the custom post', 'custompost-mutations'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'update' => SchemaDefinition::TYPE_ID,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'update':
                return SchemaDefinitionHelpers::getCreateUpdateCustomPostSchemaFieldArgs(
                    $typeResolver,
                    $fieldName,
                    false,
                    $this->resolveFieldTypeResolverClass($typeResolver, $fieldName)
                );
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
            case 'update':
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
        $post = $resultItem;
        switch ($fieldName) {
            case 'update':
                $fieldArgs[MutationInputProperties::ID] = $typeResolver->getID($post);
                break;
        }

        return $fieldArgs;
    }
}
