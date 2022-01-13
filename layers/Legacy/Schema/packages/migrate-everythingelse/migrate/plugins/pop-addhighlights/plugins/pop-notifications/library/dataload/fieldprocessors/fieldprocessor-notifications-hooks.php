<?php
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\Notifications\TypeResolvers\ObjectType\NotificationObjectTypeResolver;
use PoPSchema\Users\Facades\UserTypeAPIFacade;

class PoPTheme_Wassup_AAL_PoP_DataLoad_ObjectTypeFieldResolver_Notifications extends AbstractObjectTypeFieldResolver
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
        return $notification->object_type == 'Post' && in_array(
            $notification->action,
            [
                AAL_POP_ACTION_POST_HIGHLIGHTEDFROMPOST,
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
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $notification = $object;
        switch ($fieldName) {
            case 'icon':
                // URL depends basically on the action performed on the object type
                switch ($notification->action) {
                    case AAL_POP_ACTION_POST_HIGHLIGHTEDFROMPOST:
                        return getRouteIcon(POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS, false);
                }
                return null;

            case 'url':
                switch ($notification->action) {
                    case AAL_POP_ACTION_POST_HIGHLIGHTEDFROMPOST:
                        // Can't point to the posted article since we don't have the information (object_id is the original, referenced post, not the referencing one),
                        // so the best next thing is to point to the tab of all related content of the original post
                        return RequestUtils::addRoute($customPostTypeAPI->getPermalink($notification->object_id), POP_ADDHIGHLIGHTS_ROUTE_HIGHLIGHTS);
                }
                return null;

            case 'message':
                switch ($notification->action) {
                    case AAL_POP_ACTION_POST_HIGHLIGHTEDFROMPOST:
                        return sprintf(
                            TranslationAPIFacade::getInstance()->__('<strong>%s</strong> added a highlight from <strong>%s</strong>', 'poptheme-wassup'),
                            $userTypeAPI->getUserDisplayName($notification->user_id),
                            $customPostTypeAPI->getTitle($notification->object_id)
                        );
                }
                return null;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new PoPTheme_Wassup_AAL_PoP_DataLoad_ObjectTypeFieldResolver_Notifications())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS, 20);
