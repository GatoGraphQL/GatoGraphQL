<?php

declare(strict_types=1);

namespace PoPSchema\EverythingElse\FieldResolvers;

use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoPSchema\Tags\TypeResolvers\Object\AbstractTagTypeResolver;
use PoPSchema\EverythingElse\Misc\TagHelpers;

class TagFunctionalFieldResolver extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return [
            AbstractTagTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'symbol',
            'symbolnamedescription',
            'namedescription',
            'symbolname',
        ];
    }

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        $types = [
            'symbol' => SchemaDefinition::TYPE_STRING,
            'symbolnamedescription' => SchemaDefinition::TYPE_STRING,
            'namedescription' => SchemaDefinition::TYPE_STRING,
            'symbolname' => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'symbol' => $this->translationAPI->__('Tag symbol', 'pop-everythingelse'),
            'symbolnamedescription' => $this->translationAPI->__('Tag symbol and description', 'pop-everythingelse'),
            'namedescription' => $this->translationAPI->__('Tag and description', 'pop-everythingelse'),
            'symbolname' => $this->translationAPI->__('Symbol and tag', 'pop-everythingelse'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($relationalTypeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $applicationtaxonomyapi = \PoP\ApplicationTaxonomies\FunctionAPIFactory::getInstance();
        $tag = $resultItem;
        switch ($fieldName) {
            case 'symbol':
                return TagHelpers::getTagSymbol();

            case 'symbolnamedescription':
                return TagHelpers::getTagSymbolNameDescription($tag);

            case 'namedescription':
                return TagHelpers::getTagNameDescription($tag);

            case 'symbolname':
                return $applicationtaxonomyapi->getTagSymbolName($tag);
        }

        return parent::resolveValue($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
