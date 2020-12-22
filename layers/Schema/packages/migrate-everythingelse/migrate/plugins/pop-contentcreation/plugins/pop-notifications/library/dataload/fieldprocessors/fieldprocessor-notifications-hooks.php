<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\Route\RouteUtils;
use PoPSchema\Notifications\TypeResolvers\NotificationTypeResolver;
use PoP\ComponentModel\State\ApplicationState;

class PoP_ContentCreation_DataLoad_FieldResolver_Notifications extends AbstractDBDataFieldResolver
{
    public static function getClassesToAttachTo(): array
    {
        return array(NotificationTypeResolver::class);
    }

    public static function getFieldNamesToResolve(): array
    {
        return [
            'icon',
            'url',
            'target',
            'message',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
            'icon' => SchemaDefinition::TYPE_STRING,
            'url' => SchemaDefinition::TYPE_URL,
            'target' => SchemaDefinition::TYPE_STRING,
            'message' => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'icon' => $translationAPI->__('', ''),
            'url' => $translationAPI->__('', ''),
            'target' => $translationAPI->__('', ''),
            'message' => $translationAPI->__('', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveCanProcessResultItem(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): bool {
        $notification = $resultItem;
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
        $vars = ApplicationState::getVars();
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        $cmseditpostsapi = \PoP\EditPosts\FunctionAPIFactory::getInstance();
        $notification = $resultItem;
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
                            $cmsusersapi->getUserDisplayName($notification->user_id),
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
                            $cmsusersapi->getUserDisplayName($notification->user_id),
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

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
PoP_ContentCreation_DataLoad_FieldResolver_Notifications::attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS, 20);
