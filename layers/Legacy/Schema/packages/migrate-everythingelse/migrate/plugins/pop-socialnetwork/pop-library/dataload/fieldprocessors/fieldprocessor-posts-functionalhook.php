<?php
use PoP\Engine\Route\RouteUtils;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CustomPosts\FieldInterfaceResolvers\IsCustomPostFieldInterfaceResolver;
use PoP\ComponentModel\FieldResolvers\AbstractFunctionalFieldResolver;

class GD_SocialNetwork_DataLoad_FieldResolver_FunctionalPosts extends AbstractFunctionalFieldResolver
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

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
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
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
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
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
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
                    \PoPSchema\Posts\Constants\InputNames::POST_ID => $typeResolver->getID($post),
                ], RouteUtils::getRouteURL(POP_SOCIALNETWORK_ROUTE_RECOMMENDPOST));

            case 'unrecommendPostURL':
                return GeneralUtils::addQueryArgs([
                    \PoPSchema\Posts\Constants\InputNames::POST_ID => $typeResolver->getID($post),
                ], RouteUtils::getRouteURL(POP_SOCIALNETWORK_ROUTE_UNRECOMMENDPOST));

            case 'recommendPostCountPlus1':
                if ($count = $typeResolver->resolveValue($resultItem, 'recommendPostCount', $variables, $expressions, $options)) {
                    return $count+1;
                }
                return 1;

            case 'upvotePostURL':
                return GeneralUtils::addQueryArgs([
                    \PoPSchema\Posts\Constants\InputNames::POST_ID => $typeResolver->getID($post),
                ], RouteUtils::getRouteURL(POP_SOCIALNETWORK_ROUTE_UPVOTEPOST));

            case 'undoUpvotePostURL':
                return GeneralUtils::addQueryArgs([
                    \PoPSchema\Posts\Constants\InputNames::POST_ID => $typeResolver->getID($post),
                ], RouteUtils::getRouteURL(POP_SOCIALNETWORK_ROUTE_UNDOUPVOTEPOST));

            case 'upvotePostCountPlus1':
                if ($count = $typeResolver->resolveValue($resultItem, 'upvotePostCount', $variables, $expressions, $options)) {
                    return $count+1;
                }
                return 1;

            case 'downvotePostURL':
                return GeneralUtils::addQueryArgs([
                    \PoPSchema\Posts\Constants\InputNames::POST_ID => $typeResolver->getID($post),
                ], RouteUtils::getRouteURL(POP_SOCIALNETWORK_ROUTE_DOWNVOTEPOST));

            case 'undoDownvotePostURL':
                return GeneralUtils::addQueryArgs([
                    \PoPSchema\Posts\Constants\InputNames::POST_ID => $typeResolver->getID($post),
                ], RouteUtils::getRouteURL(POP_SOCIALNETWORK_ROUTE_UNDODOWNVOTEPOST));

            case 'downvotePostCountPlus1':
                if ($count = $typeResolver->resolveValue($resultItem, 'downvotePostCount', $variables, $expressions, $options)) {
                    return $count+1;
                }
                return 1;
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new GD_SocialNetwork_DataLoad_FieldResolver_FunctionalPosts())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS);
