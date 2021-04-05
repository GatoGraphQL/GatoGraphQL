<?php

declare(strict_types=1);

namespace PoPSchema\Tags\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
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
            'name',
            'slug',
            'description',
            'count',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
            'url' => SchemaDefinition::TYPE_URL,
            'name' => SchemaDefinition::TYPE_STRING,
            'slug' => SchemaDefinition::TYPE_STRING,
            'description' => SchemaDefinition::TYPE_STRING,
            'count' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'url' => $translationAPI->__('Tag URL', 'pop-tags'),
            'name' => $translationAPI->__('Tag', 'pop-tags'),
            'slug' => $translationAPI->__('Tag slug', 'pop-tags'),
            'description' => $translationAPI->__('Tag description', 'pop-tags'),
            'count' => $translationAPI->__('Number of custom posts containing this tag', 'pop-tags'),
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
        $cmstagsresolver = $this->getTypeAPI();
        $tagapi = $this->getTypeAPI();
        $tag = $resultItem;
        switch ($fieldName) {
            case 'url':
                return $tagapi->getTagLink($typeResolver->getID($tag));

            case 'name':
                return $cmstagsresolver->getTagName($tag);

            case 'slug':
                return $cmstagsresolver->getTagSlug($tag);

            case 'description':
                return $cmstagsresolver->getTagDescription($tag);

            case 'count':
                return $cmstagsresolver->getTagItemCount($tag);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
