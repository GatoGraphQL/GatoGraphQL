<?php
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\Route\RouteUtils;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;
use PoPSchema\Notifications\TypeResolvers\ObjectType\NotificationObjectTypeResolver;

class WSL_AAL_PoP_DataLoad_ObjectTypeFieldResolver_Notifications extends AbstractObjectTypeFieldResolver
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
     * @todo This function has been removed, adapt it to whatever needs be done!
     * @param array<string, mixed> $fieldArgs
     */
    public function resolveCanProcessObject(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldDataAccessorInterface $fieldDataAccessor,
        object $object,
    ): bool {
        $notification = $object;
        return $notification->object_type == 'User' && in_array(
            $notification->action,
            [
                WSL_AAL_POP_ACTION_USER_REQUESTCHANGEEMAIL,
            ]
        );
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor,
        \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $notification = $object;
        $cmsapplicationapi = \PoP\Application\FunctionAPIFactory::getInstance();
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        switch ($field->getName()) {
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
                            $userTypeAPI->getUserEmail($user_id),
                            $cmsapplicationapi->getSiteName()
                        );
                }
                return null;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}

// Static Initialization: Attach
(new WSL_AAL_PoP_DataLoad_ObjectTypeFieldResolver_Notifications())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS, 20);
