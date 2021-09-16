<?php
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\CustomPosts\TypeResolvers\UnionType\CustomPostUnionTypeResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\Users\Facades\UserTypeAPIFacade;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;

class GD_SocialNetwork_DataLoad_ObjectTypeFieldResolver_Users extends AbstractObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'recommendsCustomPosts',
            'followers',
            'following',
            'followersCount',
        ];
    }

    public function getFieldTypeResolverClass(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string
    {
        $types = [
            'followersCount' => \PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver::class,
        ];
        return $types[$fieldName] ?? parent::getFieldTypeResolverClass($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match($fieldName) {
            'followersCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'recommendsCustomPosts',
            'followers',
            'following'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'recommendsCustomPosts' => $translationAPI->__('', ''),
            'followers' => $translationAPI->__('', ''),
            'following' => $translationAPI->__('', ''),
            'followersCount' => $translationAPI->__('', ''),
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
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $user = $object;
        switch ($fieldName) {
            case 'recommendsCustomPosts':
                $query = [];
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsAuthorrecommendedposts($query, $objectTypeResolver->getID($user));
                return $customPostTypeAPI->getCustomPosts($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'followers':
                $query = [];
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsAuthorfollowers($query, $objectTypeResolver->getID($user));
                return $userTypeAPI->getUsers($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'following':
                $query = [];
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsAuthorfollowingusers($query, $objectTypeResolver->getID($user));
                return $userTypeAPI->getUsers($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'followersCount':
                return \PoPSchema\UserMeta\Utils::getUserMeta($objectTypeResolver->getID($user), GD_METAKEY_PROFILE_FOLLOWERSCOUNT, true);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function getFieldTypeResolverClass(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string
    {
        switch ($fieldName) {
            case 'recommendsCustomPosts':
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetObjectTypeResolverClass(CustomPostUnionTypeResolver::class);

            case 'followers':
            case 'following':
                return UserObjectTypeResolver::class;
        }

        return parent::getFieldTypeResolverClass($objectTypeResolver, $fieldName);
    }
}

// Static Initialization: Attach
(new GD_SocialNetwork_DataLoad_ObjectTypeFieldResolver_Users())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS);
