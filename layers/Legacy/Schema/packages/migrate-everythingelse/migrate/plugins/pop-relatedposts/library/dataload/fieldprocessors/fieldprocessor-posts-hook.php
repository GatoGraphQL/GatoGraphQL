<?php

use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\CustomPosts\TypeResolvers\Interface\IsCustomPostInterfaceTypeResolver;
use PoPSchema\CustomPosts\TypeResolvers\Union\CustomPostUnionTypeResolver;

class PoP_RelatedPosts_DataLoad_FieldResolver_Posts extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return [
            IsCustomPostInterfaceTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'references',
            'hasReferences',
            'referencedby',
            'hasReferencedBy',
            'referencedByCount',
        ];
    }

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        $types = [
            'hasReferences' => SchemaDefinition::TYPE_BOOL,
            'hasReferencedBy' => SchemaDefinition::TYPE_BOOL,
            'referencedByCount' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?int
    {
        return match($fieldName) {
            'hasReferences',
            'hasReferencedBy',
            'referencedByCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'references',
            'referencedby'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($relationalTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'references' => $translationAPI->__('', ''),
            'hasReferences' => $translationAPI->__('', ''),
            'referencedby' => $translationAPI->__('', ''),
            'hasReferencedBy' => $translationAPI->__('', ''),
            'referencedByCount' => $translationAPI->__('', ''),
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
        $post = $resultItem;
        switch ($fieldName) {
            case 'references':
                return \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($relationalTypeResolver->getID($post), GD_METAKEY_POST_REFERENCES) ?? [];

            case 'hasReferences':
                $references = $relationalTypeResolver->resolveValue($resultItem, 'references', $variables, $expressions, $options);
                return !empty($references);

            case 'referencedby':
                return PoP_RelatedPosts_SectionUtils::getReferencedby($relationalTypeResolver->getID($post));

            case 'hasReferencedBy':
                $referencedby = $relationalTypeResolver->resolveValue($resultItem, 'referencedby', $variables, $expressions, $options);
                return !empty($referencedby);

            case 'referencedByCount':
                $referencedby = $relationalTypeResolver->resolveValue($resultItem, 'referencedby', $variables, $expressions, $options);
                return count($referencedby);
        }

        return parent::resolveValue($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function getFieldTypeResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'references':
            case 'referencedby':
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolverClass(CustomPostUnionTypeResolver::class);
        }

        return parent::getFieldTypeResolverClass($relationalTypeResolver, $fieldName);
    }
}

// Static Initialization: Attach
(new PoP_RelatedPosts_DataLoad_FieldResolver_Posts())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS);
