<?php
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Notifications\TypeResolvers\NotificationTypeResolver;
use PoPSchema\Users\Facades\UserTypeAPIFacade;

class PoP_AAL_UserAvatar_DataLoad_FieldResolver_Notification extends AbstractDBDataFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(NotificationTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'icon',
            'url',
            'message',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'icon' => SchemaDefinition::TYPE_STRING,
            'url' => SchemaDefinition::TYPE_URL,
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
        TypeResolverInterface $typeResolver,
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

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new PoP_AAL_UserAvatar_DataLoad_FieldResolver_Notification())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS, 20);
