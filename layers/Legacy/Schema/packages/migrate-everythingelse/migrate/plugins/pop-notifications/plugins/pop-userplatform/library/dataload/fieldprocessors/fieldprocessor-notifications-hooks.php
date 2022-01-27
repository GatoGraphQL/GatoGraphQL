<?php
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\Notifications\TypeResolvers\ObjectType\NotificationObjectTypeResolver;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;

class PoP_Notifications_UserPlatform_DataLoad_ObjectTypeFieldResolver_Notifications extends AbstractObjectTypeFieldResolver
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
        return $notification->object_type == 'User' && in_array(
            $notification->action,
            [
                AAL_POP_ACTION_USER_WELCOMENEWUSER,
                AAL_POP_ACTION_USER_CHANGEDPASSWORD,
                AAL_POP_ACTION_USER_UPDATEDPROFILE,
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
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $notification = $object;
        switch ($fieldName) {
            case 'icon':
                switch ($notification->action) {
                    case AAL_POP_ACTION_USER_WELCOMENEWUSER:
                        return 'fa-exclamation-circle';
                }

                $routes = array(
                    AAL_POP_ACTION_USER_CHANGEDPASSWORD => POP_USERPLATFORM_ROUTE_CHANGEPASSWORDPROFILE,
                    AAL_POP_ACTION_USER_UPDATEDPROFILE => POP_USERPLATFORM_ROUTE_EDITPROFILE,
                );
                return getRouteIcon($routes[$notification->action], false);

            case 'url':
                switch ($notification->action) {
                    case AAL_POP_ACTION_USER_WELCOMENEWUSER:
                        return POP_NOTIFICATIONS_URLPLACEHOLDER_USERWELCOME;

                    case AAL_POP_ACTION_USER_CHANGEDPASSWORD:
                        return null;

                    case AAL_POP_ACTION_USER_UPDATEDPROFILE:
                        return $userTypeAPI->getUserURL($notification->user_id);
                }
                return null;

            case 'message':
                switch ($notification->action) {
                    case AAL_POP_ACTION_USER_WELCOMENEWUSER:
                        return sprintf(
                            TranslationAPIFacade::getInstance()->__('<strong>Welcome to %s, %s!</strong><br/>Check out here what is the purpose of this website', 'pop-notifications'),
                            $cmsapplicationapi->getSiteName(),
                            $notification->object_name //$userTypeAPI->getUserDisplayName($notification->object_id),
                        );
                }

                $messages = array(
                    AAL_POP_ACTION_USER_CHANGEDPASSWORD => TranslationAPIFacade::getInstance()->__('<strong>%s</strong> changed the password', 'pop-notifications'),
                    AAL_POP_ACTION_USER_UPDATEDPROFILE => TranslationAPIFacade::getInstance()->__('<strong>%s</strong> updated the user profile', 'pop-notifications'),
                );
                return sprintf(
                    $messages[$notification->action],
                    $userTypeAPI->getUserDisplayName($notification->user_id)
                );
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new PoP_Notifications_UserPlatform_DataLoad_ObjectTypeFieldResolver_Notifications())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS, 20);
