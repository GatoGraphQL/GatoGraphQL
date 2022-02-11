<?php
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\Notifications\TypeResolvers\ObjectType\NotificationObjectTypeResolver;
use PoPCMSSchema\PostTags\Facades\PostTagTypeAPIFacade;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;

class PoP_SocialNetwork_DataLoad_ObjectTypeFieldResolver_Notifications extends AbstractObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            NotificationObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'icon',
            'url',
            'message',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): \PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface
    {
        return match($fieldName) {
            'icon' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'url' => \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver::class,
            'message' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match($fieldName) {
            'icon' => $translationAPI->__('', ''),
            'url' => $translationAPI->__('', ''),
            'message' => $translationAPI->__('', ''),
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
        $notification = $object;
        return (
            $notification->object_type == 'Post' && in_array(
                $notification->action,
                [
                    AAL_POP_ACTION_POST_UNDIDUPVOTEPOST,
                    AAL_POP_ACTION_POST_UNDIDDOWNVOTEPOST,
                    AAL_POP_ACTION_POST_RECOMMENDEDPOST,
                    AAL_POP_ACTION_POST_UNRECOMMENDEDPOST,
                    AAL_POP_ACTION_POST_UPVOTEDPOST,
                    AAL_POP_ACTION_POST_DOWNVOTEDPOST,
                ]
            )
        ) || (
            $notification->object_type == 'User' && in_array(
                $notification->action,
                [
                    AAL_POP_ACTION_USER_FOLLOWSUSER,
                    AAL_POP_ACTION_USER_UNFOLLOWSUSER,
                ]
            )
        ) || (
            $notification->object_type == 'Taxonomy' && $notification->object_subtype == 'Tag' && in_array(
                $notification->action,
                [
                    AAL_POP_ACTION_USER_SUBSCRIBEDTOTAG,
                    AAL_POP_ACTION_USER_UNSUBSCRIBEDFROMTAG,
                ]
            )
        );
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
        \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        array $options = []
    ): mixed {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
        $applicationtaxonomyapi = \PoP\ApplicationTaxonomies\FunctionAPIFactory::getInstance();
        $notification = $object;
        switch ($fieldName) {
            case 'icon':
                // URL depends basically on the action performed on the object type
                switch ($notification->object_type) {
                    case 'Post':
                        switch ($notification->action) {
                            case AAL_POP_ACTION_POST_UNDIDUPVOTEPOST:
                            case AAL_POP_ACTION_POST_UNDIDDOWNVOTEPOST:
                                $icons = array(
                                    AAL_POP_ACTION_POST_UNDIDUPVOTEPOST => 'fa-remove',
                                    AAL_POP_ACTION_POST_UNDIDDOWNVOTEPOST => 'fa-remove',
                                );
                                return $icons[$notification->action];

                            case AAL_POP_ACTION_POST_RECOMMENDEDPOST:
                            case AAL_POP_ACTION_POST_UNRECOMMENDEDPOST:
                            case AAL_POP_ACTION_POST_UPVOTEDPOST:
                            case AAL_POP_ACTION_POST_DOWNVOTEDPOST:
                                $routes = array(
                                    AAL_POP_ACTION_POST_RECOMMENDEDPOST => POP_SOCIALNETWORK_ROUTE_RECOMMENDPOST,
                                    AAL_POP_ACTION_POST_UNRECOMMENDEDPOST => POP_SOCIALNETWORK_ROUTE_UNRECOMMENDPOST,
                                    AAL_POP_ACTION_POST_UPVOTEDPOST => POP_SOCIALNETWORK_ROUTE_UPVOTEPOST,
                                    AAL_POP_ACTION_POST_DOWNVOTEDPOST => POP_SOCIALNETWORK_ROUTE_DOWNVOTEPOST,
                                );
                                return getRouteIcon($routes[$notification->action], false);
                        }
                        return null;

                    case 'User':
                        switch ($notification->action) {
                            case AAL_POP_ACTION_USER_FOLLOWSUSER:
                                return getRouteIcon(POP_SOCIALNETWORK_ROUTE_FOLLOWINGUSERS, false);

                            case AAL_POP_ACTION_USER_UNFOLLOWSUSER:
                                return 'fa-remove';
                        }
                        return null;

                    case 'Taxonomy':
                        switch ($notification->object_subtype) {
                            case 'Tag':
                                switch ($notification->action) {
                                    case AAL_POP_ACTION_USER_SUBSCRIBEDTOTAG:
                                        return getRouteIcon(POP_SOCIALNETWORK_ROUTE_SUBSCRIBERS, false);

                                    case AAL_POP_ACTION_USER_UNSUBSCRIBEDFROMTAG:
                                        return 'fa-remove';
                                }
                                return null;
                        }
                        return null;
                }
                return null;

            case 'url':
                // URL depends basically on the action performed on the object type
                switch ($notification->object_type) {
                    case 'User':
                        switch ($notification->action) {
                            case AAL_POP_ACTION_USER_FOLLOWSUSER:
                            case AAL_POP_ACTION_USER_UNFOLLOWSUSER:
                                // If the user is the object of this action, then point the link to the user who is doing the action
                                if (\PoP\Root\App::getState('current-user-id') == $notification->object_id) {
                                    return $userTypeAPI->getUserURL($notification->user_id);
                                }
                                return $userTypeAPI->getUserURL($notification->object_id);
                        }
                        return null;

                    case 'Taxonomy':
                        switch ($notification->object_subtype) {
                            case 'Tag':
                                switch ($notification->action) {
                                    case AAL_POP_ACTION_USER_SUBSCRIBEDTOTAG:
                                    case AAL_POP_ACTION_USER_UNSUBSCRIBEDFROMTAG:
                                        return $postTagTypeAPI->getTagURL($notification->object_id);
                                }
                                return null;
                        }
                        return null;
                }
                return null;

            case 'message':
                // Message depends basically on the action performed on the object type
                switch ($notification->object_type) {
                    case 'Post':
                        switch ($notification->action) {
                            case AAL_POP_ACTION_POST_RECOMMENDEDPOST:
                            case AAL_POP_ACTION_POST_UNRECOMMENDEDPOST:
                            case AAL_POP_ACTION_POST_UPVOTEDPOST:
                            case AAL_POP_ACTION_POST_UNDIDUPVOTEPOST:
                            case AAL_POP_ACTION_POST_DOWNVOTEDPOST:
                            case AAL_POP_ACTION_POST_UNDIDDOWNVOTEPOST:
                                $messages = array(
                                    AAL_POP_ACTION_POST_RECOMMENDEDPOST => TranslationAPIFacade::getInstance()->__('<strong>%1$s</strong> has recommended %2$s <strong>%3$s</strong>', 'pop-notifications'),
                                    AAL_POP_ACTION_POST_UNRECOMMENDEDPOST => TranslationAPIFacade::getInstance()->__('<strong>%1$s</strong> stopped recommending %2$s <strong>%3$s</strong>', 'pop-notifications'),
                                    AAL_POP_ACTION_POST_UPVOTEDPOST => TranslationAPIFacade::getInstance()->__('<strong>%1$s</strong> upvoted %2$s <strong>%3$s</strong>', 'pop-notifications'),
                                    AAL_POP_ACTION_POST_UNDIDUPVOTEPOST => TranslationAPIFacade::getInstance()->__('<strong>%1$s</strong> stopped upvoting %2$s <strong>%3$s</strong>', 'pop-notifications'),
                                    AAL_POP_ACTION_POST_DOWNVOTEDPOST => TranslationAPIFacade::getInstance()->__('<strong>%1$s</strong> downvoted %2$s <strong>%3$s</strong>', 'pop-notifications'),
                                    AAL_POP_ACTION_POST_UNDIDDOWNVOTEPOST => TranslationAPIFacade::getInstance()->__('<strong>%1$s</strong> stopped downvoting %2$s <strong>%3$s</strong>', 'pop-notifications'),
                                );
                                return sprintf(
                                    $messages[$notification->action],
                                    $userTypeAPI->getUserDisplayName($notification->user_id),
                                    gdGetPostname($notification->object_id, 'lc'), //strtolower(gdGetPostname($notification->object_id)),
                                    $notification->object_name
                                );
                        }
                        return null;

                    case 'User':
                        switch ($notification->action) {
                            case AAL_POP_ACTION_USER_FOLLOWSUSER:
                            case AAL_POP_ACTION_USER_UNFOLLOWSUSER:
                                $messages = array(
                                    AAL_POP_ACTION_USER_FOLLOWSUSER => TranslationAPIFacade::getInstance()->__('<strong>%s</strong> is now following %s', 'pop-notifications'),
                                    AAL_POP_ACTION_USER_UNFOLLOWSUSER => TranslationAPIFacade::getInstance()->__('<strong>%s</strong> stopped following %s', 'pop-notifications'),
                                );

                                // Change the message depending if the logged in user is the object of this action
                                $recipient = (\PoP\Root\App::getState('current-user-id') == $notification->object_id) ? TranslationAPIFacade::getInstance()->__('you', 'pop-notifications') : sprintf('<strong>%s</strong>', $userTypeAPI->getUserDisplayName($notification->object_id));
                                return sprintf(
                                    $messages[$notification->action],
                                    $userTypeAPI->getUserDisplayName($notification->user_id),
                                    $recipient
                                );
                        }
                        return null;

                    case 'Taxonomy':
                        switch ($notification->object_subtype) {
                            case 'Tag':
                                switch ($notification->action) {
                                    case AAL_POP_ACTION_USER_SUBSCRIBEDTOTAG:
                                    case AAL_POP_ACTION_USER_UNSUBSCRIBEDFROMTAG:
                                        $messages = array(
                                            AAL_POP_ACTION_USER_SUBSCRIBEDTOTAG => TranslationAPIFacade::getInstance()->__('<strong>%s</strong> subscribed to <strong>%s</strong>', 'pop-notifications'),
                                            AAL_POP_ACTION_USER_UNSUBSCRIBEDFROMTAG => TranslationAPIFacade::getInstance()->__('<strong>%s</strong> unsubscribed from <strong>%s</strong>', 'pop-notifications'),
                                        );
                                        $tag = $postTagTypeAPI->getTag($notification->object_id);
                                        return sprintf(
                                            $messages[$notification->action],
                                            $userTypeAPI->getUserDisplayName($notification->user_id),
                                            $applicationtaxonomyapi->getTagSymbolName($tag)
                                        );
                                }
                                return null;
                        }
                        return null;
                }
                return null;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
    }
}

// Static Initialization: Attach
(new PoP_SocialNetwork_DataLoad_ObjectTypeFieldResolver_Notifications())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS, 20);
