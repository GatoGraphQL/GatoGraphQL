<?php

use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\WithEnumObjectTypeFieldSchemaDefinitionResolverTrait;
use PoP\ComponentModel\Misc\RequestUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\EverythingElse\Enums\MemberPrivilegeEnum;
use PoPSchema\EverythingElse\Enums\MemberStatusEnum;
use PoPSchema\EverythingElse\Enums\MemberTagEnum;
use PoPSchema\Notifications\TypeResolvers\ObjectType\NotificationObjectTypeResolver;
use PoPSchema\Users\Facades\UserTypeAPIFacade;

class URE_AAL_PoP_DataLoad_ObjectTypeFieldResolver_Notifications extends AbstractObjectTypeFieldResolver
{
    use WithEnumObjectTypeFieldSchemaDefinitionResolverTrait;

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            NotificationObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'editUserMembershipURL',
            'communityMembersURL',
            'memberstatus',
            'memberStatusByName',
            'memberprivileges',
            'memberPrivilegesByName',
            'membertags',
            'memberTagsByName',
            'icon',
            'url',
            'message',
        ];
    }

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        $types = [
            'editUserMembershipURL' => SchemaDefinition::TYPE_URL,
            'communityMembersURL' => SchemaDefinition::TYPE_URL,
            'memberstatus' => SchemaDefinition::TYPE_ENUM,
            'memberStatusByName' => SchemaDefinition::TYPE_STRING,
            'memberprivileges' => SchemaDefinition::TYPE_ENUM,
            'memberPrivilegesByName' => SchemaDefinition::TYPE_STRING,
            'membertags' => SchemaDefinition::TYPE_ENUM,
            'memberTagsByName' => SchemaDefinition::TYPE_STRING,
            'icon' => SchemaDefinition::TYPE_STRING,
            'url' => SchemaDefinition::TYPE_URL,
            'message' => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?int
    {
        return match($fieldName) {
            'memberstatus',
            'memberStatusByName',
            'memberprivileges',
            'memberPrivilegesByName',
            'membertags',
            'memberTagsByName'
                => SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($relationalTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'editUserMembershipURL' => $translationAPI->__('', ''),
            'communityMembersURL' => $translationAPI->__('', ''),
            'memberstatus' => $translationAPI->__('', ''),
            'memberStatusByName' => $translationAPI->__('', ''),
            'memberprivileges' => $translationAPI->__('', ''),
            'memberPrivilegesByName' => $translationAPI->__('', ''),
            'membertags' => $translationAPI->__('', ''),
            'memberTagsByName' => $translationAPI->__('', ''),
            'icon' => $translationAPI->__('', ''),
            'url' => $translationAPI->__('', ''),
            'message' => $translationAPI->__('', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($relationalTypeResolver, $fieldName);
    }

    protected function getSchemaDefinitionEnumName(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        switch ($fieldName) {
            case 'memberstatus':
            case 'memberprivileges':
            case 'membertags':
                $inputEnumClasses = [
                    'memberstatus' => MemberStatusEnum::class,
                    'memberprivileges' => MemberPrivilegeEnum::class,
                    'membertags' => MemberTagEnum::class,
                ];
                $enum = $instanceManager->getInstance($inputEnumClasses[$fieldName]);
                return $enum->getName();
        }
        return null;
    }

    protected function getSchemaDefinitionEnumValues(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?array
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        switch ($fieldName) {
            case 'memberstatus':
            case 'memberprivileges':
            case 'membertags':
                $inputEnumClasses = [
                    'memberstatus' => MemberStatusEnum::class,
                    'memberprivileges' => MemberPrivilegeEnum::class,
                    'membertags' => MemberTagEnum::class,
                ];
                $enum = $instanceManager->getInstance($inputEnumClasses[$fieldName]);
                return $enum->getValues();
        }
        return null;
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
        if (in_array($fieldName, [
            'icon',
            'url',
            'message',
        ])) {
            $notification = $resultItem;
            return $notification->object_type == 'User' && in_array(
                $notification->action,
                [
                    URE_AAL_POP_ACTION_USER_JOINEDCOMMUNITY,
                    URE_AAL_POP_ACTION_USER_UPDATEDUSERMEMBERSHIP,
                    URE_AAL_POP_ACTION_USER_UPDATEDCOMMUNITIES,
                ]
            );
        }
        return true;
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
        $vars = ApplicationState::getVars();
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        switch ($fieldName) {
            case 'editUserMembershipURL':
                return gdUreEditMembershipUrl($notification->user_id);

            case 'communityMembersURL':
                return RequestUtils::addRoute($userTypeAPI->getUserURL($notification->object_id), POP_USERCOMMUNITIES_ROUTE_MEMBERS);

         // ----------------------------------------
         // All fields below were copied from plugins/user-role-editor-popprocessors/pop-library/dataload/fieldresolvers/typeResolver-users-hook.php,
         // where they are applied on users, while here below they are applied on notifications
         // ----------------------------------------
            case 'memberstatus':
                // object_id is the user whose membership was updated
                $status = \PoPSchema\UserMeta\Utils::getUserMeta($notification->object_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS);

                // Filter status for the community: user_id
                return gdUreCommunityMembershipstatusFilterbycommunity($status, $notification->user_id);

            case 'memberStatusByName':
                $selected = $relationalTypeResolver->resolveValue($notification, 'memberstatus', $variables, $expressions, $options);
                $params = array(
                    'selected' => $selected
                );
                $status = new GD_URE_FormInput_MultiMemberStatus($params);
                return $status->getSelectedValue();

            case 'memberprivileges':
                $privileges = \PoPSchema\UserMeta\Utils::getUserMeta($notification->object_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERPRIVILEGES);

                // Filter status for the community: user_id
                return gdUreCommunityMembershipstatusFilterbycommunity($privileges, $notification->user_id);

            case 'memberPrivilegesByName':
                $selected = $relationalTypeResolver->resolveValue($notification, 'memberprivileges', $variables, $expressions, $options);
                $params = array(
                    'selected' => $selected
                );
                $privileges = new GD_URE_FormInput_FilterMemberPrivileges($params);
                return $privileges->getSelectedValue();

            case 'membertags':
                $tags = \PoPSchema\UserMeta\Utils::getUserMeta($notification->object_id, GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERTAGS);

                // Filter status for the community: user_id
                return gdUreCommunityMembershipstatusFilterbycommunity($tags, $notification->user_id);

            case 'memberTagsByName':
                $selected = $relationalTypeResolver->resolveValue($notification, 'membertags', $variables, $expressions, $options);
                $params = array(
                    'selected' => $selected
                );
                $tags = new GD_URE_FormInput_FilterMemberTags($params);
                return $tags->getSelectedValue();
         // ----------------------------------------

            case 'icon':
                switch ($notification->action) {
                    case URE_AAL_POP_ACTION_USER_JOINEDCOMMUNITY:
                        return getRouteIcon(POP_USERCOMMUNITIES_ROUTE_INVITENEWMEMBERS, false);

                    case URE_AAL_POP_ACTION_USER_UPDATEDUSERMEMBERSHIP:
                        return getRouteIcon(POP_USERCOMMUNITIES_ROUTE_EDITMEMBERSHIP, false);

                    case URE_AAL_POP_ACTION_USER_UPDATEDCOMMUNITIES:
                        return getRouteIcon(POP_USERCOMMUNITIES_ROUTE_MYCOMMUNITIES, false);
                }
                return null;

            case 'url':
                switch ($notification->action) {
                    case URE_AAL_POP_ACTION_USER_JOINEDCOMMUNITY:
                    case URE_AAL_POP_ACTION_USER_UPDATEDUSERMEMBERSHIP:
                    case URE_AAL_POP_ACTION_USER_UPDATEDCOMMUNITIES:
                        // Joined Community: link to the profile of the user joining
                        // Updated User Membership: link to the profile of the community
                        return $userTypeAPI->getUserURL($notification->user_id);
                }
                return null;

            case 'message':
                switch ($notification->action) {
                    case URE_AAL_POP_ACTION_USER_JOINEDCOMMUNITY:
                        return sprintf(
                            TranslationAPIFacade::getInstance()->__('<strong>%s</strong> has joined <strong>%s</strong>', 'ure-pop'),
                            $userTypeAPI->getUserDisplayName($notification->user_id),
                            $userTypeAPI->getUserDisplayName($notification->object_id)
                        );

                    case URE_AAL_POP_ACTION_USER_UPDATEDUSERMEMBERSHIP:
                        // Change the message depending if the logged in user is the object of this action
                        $recipient = ($vars['global-userstate']['current-user-id'] == $notification->object_id) ? TranslationAPIFacade::getInstance()->__('your', 'ure-pop') : sprintf('<strong>%s</strong>’s', $cmsengineapi->getUserDisplayName($notification->object_id));
                        return sprintf(
                            TranslationAPIFacade::getInstance()->__('<strong>%s</strong> has updated %s membership settings:', 'ure-pop'),
                            $userTypeAPI->getUserDisplayName($notification->user_id),
                            $recipient
                        );

                    case URE_AAL_POP_ACTION_USER_UPDATEDCOMMUNITIES:
                        $messages = array(
                            URE_AAL_POP_ACTION_USER_UPDATEDCOMMUNITIES => TranslationAPIFacade::getInstance()->__('<strong>%s</strong> updated the communities', 'ure-pop'),
                        );
                        return sprintf(
                            $messages[$notification->action],
                            $userTypeAPI->getUserDisplayName($notification->user_id)
                        );
                }
                return null;
        }

        return parent::resolveValue($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new URE_AAL_PoP_DataLoad_ObjectTypeFieldResolver_Notifications())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS, 20);
