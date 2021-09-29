<?php

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;

class PoP_RelatedPosts_DataLoad_ObjectTypeFieldResolver_Posts extends AbstractObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostObjectTypeResolver::class,
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

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): \PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'hasReferences'
                => \PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver::class,
            'hasReferencedBy'
                => \PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver::class,
            'referencedByCount'
                => \PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver::class,
            'references',
            'referencedby'
                => CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
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
                => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'references' => $translationAPI->__('', ''),
            'hasReferences' => $translationAPI->__('', ''),
            'referencedby' => $translationAPI->__('', ''),
            'hasReferencedBy' => $translationAPI->__('', ''),
            'referencedByCount' => $translationAPI->__('', ''),
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
        object $object,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $post = $object;
        switch ($fieldName) {
            case 'references':
                return \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($objectTypeResolver->getID($post), GD_METAKEY_POST_REFERENCES) ?? [];

            case 'hasReferences':
                $references = $objectTypeResolver->resolveValue($object, 'references', $variables, $expressions, $options);
                return !empty($references);

            case 'referencedby':
                return PoP_RelatedPosts_SectionUtils::getReferencedby($objectTypeResolver->getID($post));

            case 'hasReferencedBy':
                $referencedby = $objectTypeResolver->resolveValue($object, 'referencedby', $variables, $expressions, $options);
                return !empty($referencedby);

            case 'referencedByCount':
                $referencedby = $objectTypeResolver->resolveValue($object, 'referencedby', $variables, $expressions, $options);
                return count($referencedby);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new PoP_RelatedPosts_DataLoad_ObjectTypeFieldResolver_Posts())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS);
