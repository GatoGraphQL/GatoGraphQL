<?php
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Hooks\HooksAPIFacade;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\Notifications\TypeResolvers\ObjectType\NotificationObjectTypeResolver;
use PoPSchema\Users\Facades\UserTypeAPIFacade;

class PoP_ContentCreation_DataLoad_ObjectTypeFieldResolver_Notifications extends AbstractObjectTypeFieldResolver
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
            'target',
            'message',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): \PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface
    {
        return match($fieldName) {
            'icon' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'url' => \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver::class,
            'target' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
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
            'target' => $translationAPI->__('', ''),
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
        return $notification->object_type == 'Post' && in_array(
            $notification->action,
            [
                AAL_POP_ACTION_POST_APPROVEDPOST,
                AAL_POP_ACTION_POST_DRAFTEDPOST,
                AAL_POP_ACTION_POST_TRASHEDPOST,
                AAL_POP_ACTION_POST_SPAMMEDPOST,
                AAL_POP_ACTION_POST_CREATEDPENDINGPOST,
                AAL_POP_ACTION_POST_CREATEDDRAFTPOST,
                AAL_POP_ACTION_POST_CREATEDPOST,
                AAL_POP_ACTION_POST_UPDATEDPOST,
                AAL_POP_ACTION_POST_UPDATEDPENDINGPOST,
                AAL_POP_ACTION_POST_UPDATEDDRAFTPOST,
                AAL_POP_ACTION_POST_REMOVEDPOST,
            ]
        );
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
        $cmseditpostsapi = \PoP\EditPosts\FunctionAPIFactory::getInstance();
        $notification = $object;
        switch ($fieldName) {
            case 'icon':
                $icons = array(
                    AAL_POP_ACTION_POST_APPROVEDPOST => 'fa-check',
                    AAL_POP_ACTION_POST_DRAFTEDPOST => 'fa-exclamation',
                    AAL_POP_ACTION_POST_TRASHEDPOST => 'fa-trash',
                    AAL_POP_ACTION_POST_SPAMMEDPOST => 'fa-trash',
                );
                return $icons[$notification->action];

            case 'url':
                // URL depends basically on the action performed on the object type
                switch ($notification->action) {
                    case AAL_POP_ACTION_POST_DRAFTEDPOST:
                    case AAL_POP_ACTION_POST_CREATEDPENDINGPOST:
                    case AAL_POP_ACTION_POST_CREATEDDRAFTPOST:
                        return $cmseditpostsapi->getEditPostLink($notification->object_id);

                    case AAL_POP_ACTION_POST_TRASHEDPOST:
                        $routes = array(
                            AAL_POP_ACTION_POST_TRASHEDPOST => POP_CONTENTCREATION_ROUTE_MYCONTENT,
                        );
                        return RouteUtils::getRouteURL($routes[$notification->action]);

                    case AAL_POP_ACTION_POST_SPAMMEDPOST:
                        // $pages = array(
                        //     AAL_POP_ACTION_POST_SPAMMEDPOST => POP_CONTENTCREATION_PAGEPLACEHOLDER_SPAMMEDPOSTNOTIFICATION,
                        // );
                        // return $cmspagesapi->getPageURL($pages[$notification->action]);
                        return POP_CONTENTCREATION_URLPLACEHOLDER_SPAMMEDPOSTNOTIFICATION;
                }
                return null;

            case 'target':
                // URL depends basically on the action performed on the object type
                switch ($notification->action) {
                    case AAL_POP_ACTION_POST_DRAFTEDPOST:
                    case AAL_POP_ACTION_POST_CREATEDPENDINGPOST:
                    case AAL_POP_ACTION_POST_CREATEDDRAFTPOST:
                        return PoP_Application_Utils::getAddcontentTarget();
                }
                return null;

            case 'message':
                // Message depends basically on the action performed on the object type
                switch ($notification->action) {
                    case AAL_POP_ACTION_POST_CREATEDPOST:
                    case AAL_POP_ACTION_POST_CREATEDPENDINGPOST:
                    case AAL_POP_ACTION_POST_CREATEDDRAFTPOST:
                        $attributes = array(
                            AAL_POP_ACTION_POST_CREATEDPOST => '',
                            AAL_POP_ACTION_POST_CREATEDPENDINGPOST => TranslationAPIFacade::getInstance()->__('pending', 'pop-notifications'),
                            AAL_POP_ACTION_POST_CREATEDDRAFTPOST => TranslationAPIFacade::getInstance()->__('draft', 'pop-notifications'),
                        );
                        $attribute = $attributes[$notification->action];

                        // Show "posted" or "co-authored", depending on if the post has more than 1 author, and the main author is that on the first position of the array
                        $authors = gdGetPostauthors($notification->object_id);
                        if (count($authors) > 1 && $authors[0] != $notification->user_id) {
                            $message = TranslationAPIFacade::getInstance()->__('<strong>%1$s</strong> co-authored %2$s%3$s <strong>%4$s</strong>', 'pop-notifications');
                        } else {
                            $message = TranslationAPIFacade::getInstance()->__('<strong>%1$s</strong> created %2$s%3$s <strong>%4$s</strong>', 'pop-notifications');
                        }

                        // Allow PoP Social Network to make the message more precise
                        $message = HooksAPIFacade::getInstance()->applyFilters(
                            'PoP_ContentCreation_DataLoad_TypeResolver_Notifications_Hook:post-created:message',
                            $message,
                            $notification
                        );

                        return sprintf(
                            $message, //$messages[$notification->action],
                            $userTypeAPI->getUserDisplayName($notification->user_id),
                            $attribute ? sprintf("“%s” ", $attribute) : '',
                            gdGetPostname($notification->object_id, 'lc'),
                            $notification->object_name
                        );

                    case AAL_POP_ACTION_POST_UPDATEDPOST:
                    case AAL_POP_ACTION_POST_UPDATEDPENDINGPOST:
                    case AAL_POP_ACTION_POST_UPDATEDDRAFTPOST:
                    case AAL_POP_ACTION_POST_REMOVEDPOST:
                        $messages = array(
                            AAL_POP_ACTION_POST_UPDATEDPOST => TranslationAPIFacade::getInstance()->__('<strong>%1$s</strong> updated %2$s <strong>%3$s</strong>', 'pop-notifications'),
                            AAL_POP_ACTION_POST_UPDATEDPENDINGPOST => TranslationAPIFacade::getInstance()->__('<strong>%1$s</strong> updated “pending” %2$s <strong>%3$s</strong>', 'pop-notifications'),
                            AAL_POP_ACTION_POST_UPDATEDDRAFTPOST => TranslationAPIFacade::getInstance()->__('<strong>%1$s</strong> updated “draft” %2$s <strong>%3$s</strong>', 'pop-notifications'),
                            AAL_POP_ACTION_POST_REMOVEDPOST => TranslationAPIFacade::getInstance()->__('<strong>%1$s</strong> removed %2$s <strong>%3$s</strong>', 'pop-notifications'),
                        );
                        return sprintf(
                            $messages[$notification->action],
                            $userTypeAPI->getUserDisplayName($notification->user_id),
                            gdGetPostname($notification->object_id, 'lc'), //strtolower(gdGetPostname($notification->object_id)),
                            $notification->object_name
                        );

                    case AAL_POP_ACTION_POST_APPROVEDPOST:
                    case AAL_POP_ACTION_POST_DRAFTEDPOST:
                    case AAL_POP_ACTION_POST_TRASHEDPOST:
                    case AAL_POP_ACTION_POST_SPAMMEDPOST:
                        $messages = array(
                            AAL_POP_ACTION_POST_APPROVEDPOST => TranslationAPIFacade::getInstance()->__('Your %1$s <strong>%2$s</strong> was approved', 'pop-notifications'),
                            AAL_POP_ACTION_POST_DRAFTEDPOST => TranslationAPIFacade::getInstance()->__('Your %1$s <strong>%2$s</strong> was sent back to “draft”, please review it', 'pop-notifications'),
                            AAL_POP_ACTION_POST_TRASHEDPOST => TranslationAPIFacade::getInstance()->__('Your %1$s <strong>%2$s</strong> was deleted', 'pop-notifications'),
                            AAL_POP_ACTION_POST_SPAMMEDPOST => TranslationAPIFacade::getInstance()->__('Your %1$s <strong>%2$s</strong> was deleted for not adhering to our content guidelines', 'pop-notifications'),
                        );
                        return sprintf(
                            $messages[$notification->action],
                            gdGetPostname($notification->object_id, 'lc'), //strtolower(gdGetPostname($notification->object_id)),
                            $notification->object_name
                        );
                }
                return null;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new PoP_ContentCreation_DataLoad_ObjectTypeFieldResolver_Notifications())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS, 20);
