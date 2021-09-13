<?php
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\Route\RouteUtils;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostTypeResolver;

class GD_SocialNetwork_DataLoad_FieldResolver_FunctionalPosts extends AbstractObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractCustomPostTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'recommendPostURL',
            'unrecommendPostURL',
            'recommendPostCountPlus1',
            'upvotePostURL',
            'undoUpvotePostURL',
            'upvotePostCountPlus1',
            'downvotePostURL',
            'undoDownvotePostURL',
            'downvotePostCountPlus1',
        ];
    }

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        $types = [
			'recommendPostURL' => SchemaDefinition::TYPE_URL,
            'unrecommendPostURL' => SchemaDefinition::TYPE_URL,
            'recommendPostCountPlus1' => SchemaDefinition::TYPE_INT,
            'upvotePostURL' => SchemaDefinition::TYPE_URL,
            'undoUpvotePostURL' => SchemaDefinition::TYPE_URL,
            'upvotePostCountPlus1' => SchemaDefinition::TYPE_INT,
            'downvotePostURL' => SchemaDefinition::TYPE_URL,
            'undoDownvotePostURL' => SchemaDefinition::TYPE_URL,
            'downvotePostCountPlus1' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
			'recommendPostURL' => $translationAPI->__('', ''),
            'unrecommendPostURL' => $translationAPI->__('', ''),
            'recommendPostCountPlus1' => $translationAPI->__('', ''),
            'upvotePostURL' => $translationAPI->__('', ''),
            'undoUpvotePostURL' => $translationAPI->__('', ''),
            'upvotePostCountPlus1' => $translationAPI->__('', ''),
            'downvotePostURL' => $translationAPI->__('', ''),
            'undoDownvotePostURL' => $translationAPI->__('', ''),
            'downvotePostCountPlus1' => $translationAPI->__('', ''),
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
            case 'recommendPostURL':
                return GeneralUtils::addQueryArgs([
                    \PoPSchema\Posts\Constants\InputNames::POST_ID => $relationalTypeResolver->getID($post),
                ], RouteUtils::getRouteURL(POP_SOCIALNETWORK_ROUTE_RECOMMENDPOST));

            case 'unrecommendPostURL':
                return GeneralUtils::addQueryArgs([
                    \PoPSchema\Posts\Constants\InputNames::POST_ID => $relationalTypeResolver->getID($post),
                ], RouteUtils::getRouteURL(POP_SOCIALNETWORK_ROUTE_UNRECOMMENDPOST));

            case 'recommendPostCountPlus1':
                if ($count = $relationalTypeResolver->resolveValue($resultItem, 'recommendPostCount', $variables, $expressions, $options)) {
                    return $count+1;
                }
                return 1;

            case 'upvotePostURL':
                return GeneralUtils::addQueryArgs([
                    \PoPSchema\Posts\Constants\InputNames::POST_ID => $relationalTypeResolver->getID($post),
                ], RouteUtils::getRouteURL(POP_SOCIALNETWORK_ROUTE_UPVOTEPOST));

            case 'undoUpvotePostURL':
                return GeneralUtils::addQueryArgs([
                    \PoPSchema\Posts\Constants\InputNames::POST_ID => $relationalTypeResolver->getID($post),
                ], RouteUtils::getRouteURL(POP_SOCIALNETWORK_ROUTE_UNDOUPVOTEPOST));

            case 'upvotePostCountPlus1':
                if ($count = $relationalTypeResolver->resolveValue($resultItem, 'upvotePostCount', $variables, $expressions, $options)) {
                    return $count+1;
                }
                return 1;

            case 'downvotePostURL':
                return GeneralUtils::addQueryArgs([
                    \PoPSchema\Posts\Constants\InputNames::POST_ID => $relationalTypeResolver->getID($post),
                ], RouteUtils::getRouteURL(POP_SOCIALNETWORK_ROUTE_DOWNVOTEPOST));

            case 'undoDownvotePostURL':
                return GeneralUtils::addQueryArgs([
                    \PoPSchema\Posts\Constants\InputNames::POST_ID => $relationalTypeResolver->getID($post),
                ], RouteUtils::getRouteURL(POP_SOCIALNETWORK_ROUTE_UNDODOWNVOTEPOST));

            case 'downvotePostCountPlus1':
                if ($count = $relationalTypeResolver->resolveValue($resultItem, 'downvotePostCount', $variables, $expressions, $options)) {
                    return $count+1;
                }
                return 1;
        }

        return parent::resolveValue($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new GD_SocialNetwork_DataLoad_FieldResolver_FunctionalPosts())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS);
