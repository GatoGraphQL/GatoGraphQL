<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\Route\RouteUtils;
use PoPSchema\Notifications\TypeResolvers\NotificationTypeResolver;

class WSL_AAL_PoP_DataLoad_FieldResolver_Notifications extends AbstractDBDataFieldResolver
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

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
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
                WSL_AAL_POP_ACTION_USER_REQUESTCHANGEEMAIL,
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
        $notification = $resultItem;

        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        switch ($fieldName) {
            case 'icon':
                switch ($notification->action) {
                    case WSL_AAL_POP_ACTION_USER_REQUESTCHANGEEMAIL:
                        return getRouteIcon(POP_USERPLATFORM_ROUTE_EDITPROFILE, false);
                }
                return null;

            case 'url':
                switch ($notification->action) {
                // Link to the Edit Profile page
                    case WSL_AAL_POP_ACTION_USER_REQUESTCHANGEEMAIL:
                        return RouteUtils::getRouteURL(POP_USERPLATFORM_ROUTE_EDITPROFILE);
                }
                return null;

            case 'message':
                switch ($notification->action) {
                    case WSL_AAL_POP_ACTION_USER_REQUESTCHANGEEMAIL:
                        $user_id = $notification->object_id;
                        return sprintf(
                            TranslationAPIFacade::getInstance()->__('<strong>Please update your email</strong><br/>%s does not provide your email, so we set a random one for you: <em>%s</em>. Please click here to change it to your real email, or you won\'t receive notifications from the %s website', 'wsl-pop'),
                            getSocialloginProvider($user_id),
                            $cmsusersapi->getUserEmail($user_id),
                            $cmsapplicationapi->getSiteName()
                        );
                }
                return null;
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new WSL_AAL_PoP_DataLoad_FieldResolver_Notifications())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS, 20);
