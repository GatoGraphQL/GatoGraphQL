<?php
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchemaPRO\LocationPosts\TypeResolvers\ObjectType\LocationPostObjectTypeResolver;
use PoPCMSSchema\PostCategories\Facades\PostCategoryTypeAPIFacade;

class GD_Custom_Locations_ContentPostLinks_DataLoad_ObjectTypeFieldResolver_Posts extends AbstractObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            LocationPostObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'excerpt',
            'content',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): \PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface
    {
        return match($fieldName) {
            'excerpt' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'content' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'content' => SchemaTypeModifiers::NON_NULLABLE,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match($fieldName) {
            'excerpt' => $translationAPI->__('', ''),
            'content' => $translationAPI->__('', ''),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveCanProcessObject(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = []
    ): bool {
        if (in_array($fieldName, [
            'excerpt',
            'content',
        ])) {
            $locationpost = $object;
            $postCategoryTypeAPI = PostCategoryTypeAPIFacade::getInstance();
            return POP_LOCATIONPOSTLINKS_CAT_LOCATIONPOSTLINKS && $postCategoryTypeAPI->hasCategory(POP_LOCATIONPOSTLINKS_CAT_LOCATIONPOSTLINKS, $objectTypeResolver->getID($locationpost));
        }
        return true;
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
        $locationpost = $object;
        switch ($fieldName) {
            // Override fields for Location Post Links
            case 'excerpt':
                return PoP_ContentPostLinks_Utils::getLinkExcerpt($locationpost);
            case 'content':
                return PoP_ContentPostLinks_Utils::getLinkContent($locationpost);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new GD_Custom_Locations_ContentPostLinks_DataLoad_ObjectTypeFieldResolver_Posts())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS, 20);
