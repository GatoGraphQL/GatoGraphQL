<?php
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Notifications\TypeResolvers\ObjectType\NotificationObjectTypeResolver;
use PoPSchema\Users\Facades\UserTypeAPIFacade;

class PoP_Notifications_UserLogin_DataLoad_ObjectTypeFieldResolver_Notifications extends AbstractObjectTypeFieldResolver
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
                AAL_POP_ACTION_USER_LOGGEDIN,
                AAL_POP_ACTION_USER_LOGGEDOUT,
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
        $notification = $resultItem;
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        switch ($fieldName) {
            case 'icon':
                $routes = array(
                    AAL_POP_ACTION_USER_LOGGEDIN => POP_USERLOGIN_ROUTE_LOGIN,
                    AAL_POP_ACTION_USER_LOGGEDOUT => POP_USERLOGIN_ROUTE_LOGOUT,
                );
                return getRouteIcon($routes[$notification->action], false);

            case 'url':
                return $userTypeAPI->getUserURL($notification->user_id);

            case 'message':
                $messages = array(
                    AAL_POP_ACTION_USER_LOGGEDIN => TranslationAPIFacade::getInstance()->__('<strong>%s</strong> logged in', 'pop-notifications'),
                    AAL_POP_ACTION_USER_LOGGEDOUT => TranslationAPIFacade::getInstance()->__('<strong>%s</strong> logged out', 'pop-notifications'),
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
(new PoP_Notifications_UserLogin_DataLoad_ObjectTypeFieldResolver_Notifications())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS, 20);
