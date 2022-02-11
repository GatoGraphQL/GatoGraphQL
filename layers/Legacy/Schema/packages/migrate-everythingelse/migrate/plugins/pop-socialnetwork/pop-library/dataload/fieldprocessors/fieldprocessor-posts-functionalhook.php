<?php
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;

class GD_SocialNetwork_DataLoad_ObjectTypeFieldResolver_FunctionalPosts extends AbstractObjectTypeFieldResolver
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

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): \PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface
    {
        return match($fieldName) {
			'recommendPostURL' => \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver::class,
            'unrecommendPostURL' => \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver::class,
            'recommendPostCountPlus1' => \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver::class,
            'upvotePostURL' => \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver::class,
            'undoUpvotePostURL' => \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver::class,
            'upvotePostCountPlus1' => \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver::class,
            'downvotePostURL' => \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver::class,
            'undoDownvotePostURL' => \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver::class,
            'downvotePostCountPlus1' => \PoP\ComponentModel\TypeResolvers\ScalarType\IntScalarTypeResolver::class,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match($fieldName) {
			'recommendPostURL' => $translationAPI->__('', ''),
            'unrecommendPostURL' => $translationAPI->__('', ''),
            'recommendPostCountPlus1' => $translationAPI->__('', ''),
            'upvotePostURL' => $translationAPI->__('', ''),
            'undoUpvotePostURL' => $translationAPI->__('', ''),
            'upvotePostCountPlus1' => $translationAPI->__('', ''),
            'downvotePostURL' => $translationAPI->__('', ''),
            'undoDownvotePostURL' => $translationAPI->__('', ''),
            'downvotePostCountPlus1' => $translationAPI->__('', ''),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        array $variables,
        array $expressions,
        array $options = []
    ): mixed {
        $post = $object;
        switch ($fieldName) {
            case 'recommendPostURL':
                return GeneralUtils::addQueryArgs([
                    \PoPCMSSchema\Posts\Constants\InputNames::POST_ID => $objectTypeResolver->getID($post),
                ], RouteUtils::getRouteURL(POP_SOCIALNETWORK_ROUTE_RECOMMENDPOST));

            case 'unrecommendPostURL':
                return GeneralUtils::addQueryArgs([
                    \PoPCMSSchema\Posts\Constants\InputNames::POST_ID => $objectTypeResolver->getID($post),
                ], RouteUtils::getRouteURL(POP_SOCIALNETWORK_ROUTE_UNRECOMMENDPOST));

            case 'recommendPostCountPlus1':
                if ($count = $objectTypeResolver->resolveValue($object, 'recommendPostCount', $variables, $expressions, $options)) {
                    return $count+1;
                }
                return 1;

            case 'upvotePostURL':
                return GeneralUtils::addQueryArgs([
                    \PoPCMSSchema\Posts\Constants\InputNames::POST_ID => $objectTypeResolver->getID($post),
                ], RouteUtils::getRouteURL(POP_SOCIALNETWORK_ROUTE_UPVOTEPOST));

            case 'undoUpvotePostURL':
                return GeneralUtils::addQueryArgs([
                    \PoPCMSSchema\Posts\Constants\InputNames::POST_ID => $objectTypeResolver->getID($post),
                ], RouteUtils::getRouteURL(POP_SOCIALNETWORK_ROUTE_UNDOUPVOTEPOST));

            case 'upvotePostCountPlus1':
                if ($count = $objectTypeResolver->resolveValue($object, 'upvotePostCount', $variables, $expressions, $options)) {
                    return $count+1;
                }
                return 1;

            case 'downvotePostURL':
                return GeneralUtils::addQueryArgs([
                    \PoPCMSSchema\Posts\Constants\InputNames::POST_ID => $objectTypeResolver->getID($post),
                ], RouteUtils::getRouteURL(POP_SOCIALNETWORK_ROUTE_DOWNVOTEPOST));

            case 'undoDownvotePostURL':
                return GeneralUtils::addQueryArgs([
                    \PoPCMSSchema\Posts\Constants\InputNames::POST_ID => $objectTypeResolver->getID($post),
                ], RouteUtils::getRouteURL(POP_SOCIALNETWORK_ROUTE_UNDODOWNVOTEPOST));

            case 'downvotePostCountPlus1':
                if ($count = $objectTypeResolver->resolveValue($object, 'downvotePostCount', $variables, $expressions, $options)) {
                    return $count+1;
                }
                return 1;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new GD_SocialNetwork_DataLoad_ObjectTypeFieldResolver_FunctionalPosts())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS);
