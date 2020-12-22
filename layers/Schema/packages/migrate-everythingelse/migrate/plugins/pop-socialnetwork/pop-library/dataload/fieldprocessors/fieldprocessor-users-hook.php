<?php
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class GD_SocialNetwork_DataLoad_FieldResolver_Users extends AbstractDBDataFieldResolver
{
    public static function getClassesToAttachTo(): array
    {
        return array(UserTypeResolver::class);
    }

    public static function getFieldNamesToResolve(): array
    {
        return [
            'recommendsCustomPosts',
            'followers',
            'following',
            'followersCount',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
            'recommendsCustomPosts' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'followers' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'following' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'followersCount' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function isSchemaFieldResponseNonNullable(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        $nonNullableFieldNames = [
            'recommendsCustomPosts',
            'followers',
            'following',
            'followersCount',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'recommendsCustomPosts' => $translationAPI->__('', ''),
            'followers' => $translationAPI->__('', ''),
            'following' => $translationAPI->__('', ''),
            'followersCount' => $translationAPI->__('', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     * @return mixed
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ) {
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $user = $resultItem;
        switch ($fieldName) {
            case 'recommendsCustomPosts':
                $query = [];
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsAuthorrecommendedposts($query, $typeResolver->getID($user));
                return $customPostTypeAPI->getCustomPosts($query, ['return-type' => ReturnTypes::IDS]);

            case 'followers':
                $query = [];
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsAuthorfollowers($query, $typeResolver->getID($user));
                return $cmsusersapi->getUsers($query, ['return-type' => ReturnTypes::IDS]);

            case 'following':
                $query = [];
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsAuthorfollowingusers($query, $typeResolver->getID($user));
                return $cmsusersapi->getUsers($query, ['return-type' => ReturnTypes::IDS]);

            case 'followersCount':
                return \PoPSchema\UserMeta\Utils::getUserMeta($typeResolver->getID($user), GD_METAKEY_PROFILE_FOLLOWERSCOUNT, true);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'recommendsCustomPosts':
                return CustomPostUnionTypeHelpers::getCustomPostUnionOrTargetTypeResolverClass(CustomPostUnionTypeResolver::class);

            case 'followers':
            case 'following':
                return UserTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}

// Static Initialization: Attach
GD_SocialNetwork_DataLoad_FieldResolver_Users::attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS);
