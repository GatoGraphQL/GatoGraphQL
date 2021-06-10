<?php
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\Notifications\TypeResolvers\NotificationTypeResolver;
use PoPSchema\Users\Facades\UserTypeAPIFacade;

class PoPTheme_Wassup_AAL_PoP_DataLoad_FieldResolver_Notifications extends AbstractDBDataFieldResolver
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
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        $notification = $resultItem;
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

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new PoPTheme_Wassup_AAL_PoP_DataLoad_FieldResolver_Notifications())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS, 20);
