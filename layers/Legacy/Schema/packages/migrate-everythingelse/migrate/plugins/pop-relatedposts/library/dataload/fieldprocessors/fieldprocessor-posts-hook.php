<?php

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;

class PoP_RelatedPosts_DataLoad_ObjectTypeFieldResolver_Posts extends AbstractObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
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
                => \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver::class,
            'hasReferencedBy'
                => \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver::class,
            'referencedByCount'
                => \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver::class,
            'references',
            'referencedby'
                => CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
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
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match($fieldName) {
            'references' => $translationAPI->__('', ''),
            'hasReferences' => $translationAPI->__('', ''),
            'referencedby' => $translationAPI->__('', ''),
            'hasReferencedBy' => $translationAPI->__('', ''),
            'referencedByCount' => $translationAPI->__('', ''),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor,
        \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $post = $object;
        switch ($field->getName()) {
            case 'references':
                return \PoPCMSSchema\CustomPostMeta\Utils::getCustomPostMeta($objectTypeResolver->getID($post), GD_METAKEY_POST_REFERENCES) ?? [];

            case 'hasReferences':
                $references = $objectTypeResolver->resolveValue($object, 'references', $objectTypeFieldResolutionFeedbackStore);
                return !empty($references);

            case 'referencedby':
                return PoP_RelatedPosts_SectionUtils::getReferencedby($objectTypeResolver->getID($post));

            case 'hasReferencedBy':
                $referencedby = $objectTypeResolver->resolveValue($object, 'referencedby', $objectTypeFieldResolutionFeedbackStore);
                return !empty($referencedby);

            case 'referencedByCount':
                $referencedby = $objectTypeResolver->resolveValue($object, 'referencedby', $objectTypeFieldResolutionFeedbackStore);
                return count($referencedby);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}

// Static Initialization: Attach
(new PoP_RelatedPosts_DataLoad_ObjectTypeFieldResolver_Posts())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS);
