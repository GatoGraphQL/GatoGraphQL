<?php
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Notifications\TypeResolvers\ObjectType\NotificationObjectTypeResolver;
use PoPSchema\Users\Facades\UserTypeAPIFacade;

class PoP_AAL_UserAvatar_DataLoad_ObjectTypeFieldResolver_Notification extends AbstractObjectTypeFieldResolver
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
    public function resolveCanProcessObject(
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = []
    ): bool {
        $notification = $object;
        return $notification->object_type == 'User' && in_array(
            $notification->action,
            [
                AAL_POP_ACTION_USER_UPDATEDPHOTO,
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
        object $object,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $notification = $object;
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        switch ($fieldName) {
            case 'icon':
                switch ($notification->action) {
                    case AAL_POP_ACTION_USER_UPDATEDPHOTO:
                        return getRouteIcon(POP_USERAVATAR_ROUTE_EDITAVATAR, false);
                }
                return null;

            case 'url':
                switch ($notification->action) {
                    case AAL_POP_ACTION_USER_UPDATEDPHOTO:
                        return $userTypeAPI->getUserURL($notification->user_id);
                }
                return null;

            case 'message':
                $messages = array(
                    AAL_POP_ACTION_USER_UPDATEDPHOTO => TranslationAPIFacade::getInstance()->__('<strong>%s</strong> updated the user photo', 'pop-notifications'),
                );
                return sprintf(
                    $messages[$notification->action],
                    $userTypeAPI->getUserDisplayName($notification->user_id)
                );
        }

        return parent::resolveValue($relationalTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new PoP_AAL_UserAvatar_DataLoad_ObjectTypeFieldResolver_Notification())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS, 20);
