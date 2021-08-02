<?php

declare(strict_types=1);

namespace PoPSchema\Tags\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\QueriedObject\FieldInterfaceResolvers\QueryableFieldInterfaceResolver;
use PoPSchema\Tags\ComponentContracts\TagAPIRequestedContractTrait;

abstract class AbstractTagFieldResolver extends AbstractDBDataFieldResolver
{
    use TagAPIRequestedContractTrait;

    public function getImplementedFieldInterfaceResolverClasses(): array
    {
        return [
            QueryableFieldInterfaceResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'url',
            'urlPath',
            'name',
            'slug',
            'description',
            'count',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'url' => SchemaDefinition::TYPE_URL,
            'urlPath' => SchemaDefinition::TYPE_STRING,
            'name' => SchemaDefinition::TYPE_STRING,
            'slug' => SchemaDefinition::TYPE_STRING,
            'description' => SchemaDefinition::TYPE_STRING,
            'count' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'url',
            'urlPath',
            'name',
            'slug',
            'count'
                => SchemaTypeModifiers::NON_NULLABLE,
            default
                => parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'url' => $this->translationAPI->__('Tag URL', 'pop-tags'),
            'urlPath' => $this->translationAPI->__('Tag URL path', 'pop-tags'),
            'name' => $this->translationAPI->__('Tag', 'pop-tags'),
            'slug' => $this->translationAPI->__('Tag slug', 'pop-tags'),
            'description' => $this->translationAPI->__('Tag description', 'pop-tags'),
            'count' => $this->translationAPI->__('Number of custom posts containing this tag', 'pop-tags'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $tagTypeAPI = $this->getTypeAPI();
        $tag = $resultItem;
        switch ($fieldName) {
            case 'url':
                return $tagTypeAPI->getTagURL($typeResolver->getID($tag));

            case 'urlPath':
                return $tagTypeAPI->getTagURLPath($typeResolver->getID($tag));

            case 'name':
                return $tagTypeAPI->getTagName($tag);

            case 'slug':
                return $tagTypeAPI->getTagSlug($tag);

            case 'description':
                return $tagTypeAPI->getTagDescription($tag);

            case 'count':
                return $tagTypeAPI->getTagItemCount($tag);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
