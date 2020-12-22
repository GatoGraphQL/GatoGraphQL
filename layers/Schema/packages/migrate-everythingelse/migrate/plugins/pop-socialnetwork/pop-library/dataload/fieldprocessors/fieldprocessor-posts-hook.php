<?php
use PoPSchema\Users\TypeResolvers\UserTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class GD_SocialNetwork_DataLoad_FieldResolver_Posts extends AbstractDBDataFieldResolver
{
    public static function getClassesToAttachTo(): array
    {
        return [
            IsCustomPostFieldInterfaceResolver::class,
        ];
    }

    public static function getFieldNamesToResolve(): array
    {
        return [
            'taggedusers',
            'recommendedby',
            'recommendPostCount',
            'upvotePostCount',
            'downvotePostCount',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
            'taggedusers' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'recommendedby' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_ID),
            'recommendPostCount' => SchemaDefinition::TYPE_INT,
            'upvotePostCount' => SchemaDefinition::TYPE_INT,
            'downvotePostCount' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function isSchemaFieldResponseNonNullable(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        $nonNullableFieldNames = [
            'taggedusers',
            'recommendedby',
            'recommendPostCount',
            'upvotePostCount',
            'downvotePostCount',
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
            'taggedusers' => $translationAPI->__('', ''),
            'recommendedby' => $translationAPI->__('', ''),
            'recommendPostCount' => $translationAPI->__('', ''),
            'upvotePostCount' => $translationAPI->__('', ''),
            'downvotePostCount' => $translationAPI->__('', ''),
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
        $post = $resultItem;
        switch ($fieldName) {
            // Users mentioned in the post: @mentions
            case 'taggedusers':
                return \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($typeResolver->getID($post), GD_METAKEY_POST_TAGGEDUSERS) ?? [];

            case 'recommendedby':
                $query = [];
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsRecommendedby($query, $typeResolver->getID($post));
                return $cmsusersapi->getUsers($query, ['return-type' => ReturnTypes::IDS]);

            case 'recommendPostCount':
                return (int) \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($typeResolver->getID($post), GD_METAKEY_POST_RECOMMENDCOUNT, true);

            case 'upvotePostCount':
                return (int) \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($typeResolver->getID($post), GD_METAKEY_POST_UPVOTECOUNT, true);

            case 'downvotePostCount':
                return (int) \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($typeResolver->getID($post), GD_METAKEY_POST_DOWNVOTECOUNT, true);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'taggedusers':
            case 'recommendedby':
                return UserTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}

// Static Initialization: Attach
GD_SocialNetwork_DataLoad_FieldResolver_Posts::attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS);
