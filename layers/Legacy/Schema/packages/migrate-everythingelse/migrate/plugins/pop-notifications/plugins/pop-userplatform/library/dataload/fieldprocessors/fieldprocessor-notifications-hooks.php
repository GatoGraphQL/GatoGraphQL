<?php
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractDBDataFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Notifications\TypeResolvers\ObjectType\NotificationTypeResolver;
use PoPSchema\Users\Facades\UserTypeAPIFacade;

class PoP_Notifications_UserPlatform_DataLoad_FieldResolver_Notifications extends AbstractDBDataFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            NotificationTypeResolver::class,
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

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        $types = [
            'icon' => SchemaDefinition::TYPE_STRING,
            'url' => SchemaDefinition::TYPE_URL,
            'message' => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'icon' => $translationAPI->__('', ''),
            'url' => $translationAPI->__('', ''),
            'message' => $translationAPI->__('', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($relationalTypeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveCanProcessResultItem(
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): bool {
        $notification = $resultItem;
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
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $notification = $resultItem;
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

        return parent::resolveValue($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new PoP_Notifications_UserPlatform_DataLoad_FieldResolver_Notifications())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS, 20);
