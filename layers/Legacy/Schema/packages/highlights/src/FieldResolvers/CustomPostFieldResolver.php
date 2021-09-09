<?php

declare(strict_types=1);

namespace PoPSchema\Highlights\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\Object\ObjectTypeResolverInterface;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\TypeResolvers\Object\AbstractCustomPostTypeResolver;
use PoPSchema\Highlights\TypeResolvers\Object\HighlightTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class CustomPostFieldResolver extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'highlights',
            'hasHighlights',
            'highlightsCount',
        ];
    }

    public function getSchemaFieldType(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string
    {
        return match($fieldName) {
            'hasHighlights' => SchemaDefinition::TYPE_BOOL,
            'highlightsCount' => SchemaDefinition::TYPE_INT,
            default => parent::getSchemaFieldType($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'hasHighlights',
            'highlightsCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'highlights'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'highlights' => $this->translationAPI->__('', ''),
            'hasHighlights' => $this->translationAPI->__('', ''),
            'highlightsCount' => $this->translationAPI->__('', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $customPost = $resultItem;
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        switch ($fieldName) {
            case 'highlights':
                $query = array(
                    // 'fields' => 'ids',
                    'limit' => -1, // Bring all the results
                    'meta-query' => [
                        [
                            'key' => \PoPSchema\CustomPostMeta\Utils::getMetaKey(GD_METAKEY_POST_HIGHLIGHTEDPOST),
                            'value' => $objectTypeResolver->getID($customPost),
                        ],
                    ],
                    'custompost-types' => [POP_ADDHIGHLIGHTS_POSTTYPE_HIGHLIGHT],
                    'orderby' => $this->nameResolver->getName('popcms:dbcolumn:orderby:customposts:date'),
                    'order' => 'ASC',
                );

                return $customPostTypeAPI->getCustomPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'hasHighlights':
                $referencedbyCount = $objectTypeResolver->resolveValue($resultItem, 'highlightsCount', $variables, $expressions, $options);
                if (GeneralUtils::isError($referencedbyCount)) {
                    return $referencedbyCount;
                }
                return $referencedbyCount > 0;

            case 'highlightsCount':
                $referencedby = $objectTypeResolver->resolveValue($resultItem, 'highlights', $variables, $expressions, $options);
                if (GeneralUtils::isError($referencedby)) {
                    return $referencedby;
                }
                return count($referencedby);
        }

        return parent::resolveValue($objectTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function getFieldTypeResolverClass(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'highlights':
                return HighlightTypeResolver::class;
        }

        return parent::getFieldTypeResolverClass($objectTypeResolver, $fieldName);
    }
}
