<?php
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\Users\Facades\UserTypeAPIFacade;
use PoPSchema\Users\TypeResolvers\Object\UserTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;

class GD_SocialNetwork_DataLoad_FieldResolver_Posts extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return [
            IsCustomPostFieldInterfaceResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'taggedusers',
            'recommendedby',
            'recommendPostCount',
            'upvotePostCount',
            'downvotePostCount',
        ];
    }

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        $types = [
            'recommendPostCount' => SchemaDefinition::TYPE_INT,
            'upvotePostCount' => SchemaDefinition::TYPE_INT,
            'downvotePostCount' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?int
    {
        return match($fieldName) {
            'recommendPostCount',
            'upvotePostCount',
            'downvotePostCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'taggedusers',
            'recommendedby'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($relationalTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'taggedusers' => $translationAPI->__('', ''),
            'recommendedby' => $translationAPI->__('', ''),
            'recommendPostCount' => $translationAPI->__('', ''),
            'upvotePostCount' => $translationAPI->__('', ''),
            'downvotePostCount' => $translationAPI->__('', ''),
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
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $post = $resultItem;
        switch ($fieldName) {
            // Users mentioned in the post: @mentions
            case 'taggedusers':
                return \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($relationalTypeResolver->getID($post), GD_METAKEY_POST_TAGGEDUSERS) ?? [];

            case 'recommendedby':
                $query = [];
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsRecommendedby($query, $relationalTypeResolver->getID($post));
                return $userTypeAPI->getUsers($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'recommendPostCount':
                return (int) \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($relationalTypeResolver->getID($post), GD_METAKEY_POST_RECOMMENDCOUNT, true);

            case 'upvotePostCount':
                return (int) \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($relationalTypeResolver->getID($post), GD_METAKEY_POST_UPVOTECOUNT, true);

            case 'downvotePostCount':
                return (int) \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($relationalTypeResolver->getID($post), GD_METAKEY_POST_DOWNVOTECOUNT, true);
        }

        return parent::resolveValue($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function getFieldTypeResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'taggedusers':
            case 'recommendedby':
                return UserTypeResolver::class;
        }

        return parent::getFieldTypeResolverClass($relationalTypeResolver, $fieldName);
    }
}

// Static Initialization: Attach
(new GD_SocialNetwork_DataLoad_FieldResolver_Posts())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS);
