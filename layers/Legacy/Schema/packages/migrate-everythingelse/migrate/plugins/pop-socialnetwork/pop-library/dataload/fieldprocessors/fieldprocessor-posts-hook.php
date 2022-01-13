<?php
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\Users\Facades\UserTypeAPIFacade;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class GD_SocialNetwork_DataLoad_ObjectTypeFieldResolver_Posts extends AbstractObjectTypeFieldResolver
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
            'taggedusers',
            'recommendedby',
            'recommendPostCount',
            'upvotePostCount',
            'downvotePostCount',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): \PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'taggedusers':
            case 'recommendedby':
                return $this->instanceManager->getInstance(UserObjectTypeResolver::class);
        }
        return match($fieldName) {
            'recommendPostCount' => \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver::class,
            'upvotePostCount' => \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver::class,
            'downvotePostCount' => \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver::class,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
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
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match($fieldName) {
            'taggedusers' => $translationAPI->__('', ''),
            'recommendedby' => $translationAPI->__('', ''),
            'recommendPostCount' => $translationAPI->__('', ''),
            'upvotePostCount' => $translationAPI->__('', ''),
            'downvotePostCount' => $translationAPI->__('', ''),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
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
        $post = $object;
        switch ($fieldName) {
            // Users mentioned in the post: @mentions
            case 'taggedusers':
                return \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($objectTypeResolver->getID($post), GD_METAKEY_POST_TAGGEDUSERS) ?? [];

            case 'recommendedby':
                $query = [];
                PoP_Module_Processor_CustomSectionBlocksUtils::addDataloadqueryargsRecommendedby($query, $objectTypeResolver->getID($post));
                return $userTypeAPI->getUsers($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'recommendPostCount':
                return (int) \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($objectTypeResolver->getID($post), GD_METAKEY_POST_RECOMMENDCOUNT, true);

            case 'upvotePostCount':
                return (int) \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($objectTypeResolver->getID($post), GD_METAKEY_POST_UPVOTECOUNT, true);

            case 'downvotePostCount':
                return (int) \PoPSchema\CustomPostMeta\Utils::getCustomPostMeta($objectTypeResolver->getID($post), GD_METAKEY_POST_DOWNVOTECOUNT, true);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new GD_SocialNetwork_DataLoad_ObjectTypeFieldResolver_Posts())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS);
