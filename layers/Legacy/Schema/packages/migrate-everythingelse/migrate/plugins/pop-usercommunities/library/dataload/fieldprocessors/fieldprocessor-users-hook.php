<?php
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\FieldResolvers\ObjectType\WithEnumObjectTypeFieldSchemaDefinitionResolverTrait;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\EverythingElse\Enums\MemberPrivilegeEnum;
use PoPSchema\EverythingElse\Enums\MemberStatusEnum;
use PoPSchema\EverythingElse\Enums\MemberTagEnum;
use PoPSchema\Users\TypeResolvers\ObjectType\UserTypeResolver;

class GD_UserCommunities_DataLoad_ObjectTypeFieldResolver_Users extends AbstractObjectTypeFieldResolver
{
    use WithEnumObjectTypeFieldSchemaDefinitionResolverTrait;

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'memberstatus',
            'memberprivileges',
            'membertags',
            'isCommunity',
            'communities',
            'activeCommunities',
            'hasActiveCommunities',
        ];
    }

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        $types = [
            'memberstatus' => SchemaDefinition::TYPE_ENUM,
            'memberprivileges' => SchemaDefinition::TYPE_ENUM,
            'membertags' => SchemaDefinition::TYPE_ENUM,
            'isCommunity' => SchemaDefinition::TYPE_BOOL,
            'hasActiveCommunities' => SchemaDefinition::TYPE_BOOL,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?int
    {
        return match($fieldName) {
            'hasActiveCommunities'
                => SchemaTypeModifiers::NON_NULLABLE,
            'communities',
            'activeCommunities'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($relationalTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'memberstatus' => $translationAPI->__('', ''),
            'memberprivileges' => $translationAPI->__('', ''),
            'membertags' => $translationAPI->__('', ''),
            'isCommunity' => $translationAPI->__('', ''),
            'communities' => $translationAPI->__('', ''),
            'activeCommunities' => $translationAPI->__('', ''),
            'hasActiveCommunities' => $translationAPI->__('', ''),
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
        $user = $resultItem;

        switch ($fieldName) {
            case 'memberstatus':
                // All status for all communities
                $status = \PoPSchema\UserMeta\Utils::getUserMeta($relationalTypeResolver->getID($user), GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS);

                // Filter status for only this community: the logged in user
                return gdUreCommunityMembershipstatusFilterbycurrentcommunity($status);

            case 'memberprivileges':
                // All privileges for all communities
                $privileges = \PoPSchema\UserMeta\Utils::getUserMeta($relationalTypeResolver->getID($user), GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERPRIVILEGES);

                // Filter privileges for only this community: the logged in user
                return gdUreCommunityMembershipstatusFilterbycurrentcommunity($privileges);

            case 'membertags':
                // All privileges for all communities
                $tags = \PoPSchema\UserMeta\Utils::getUserMeta($relationalTypeResolver->getID($user), GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERTAGS);

                // Filter privileges for only this community: the logged in user
                return gdUreCommunityMembershipstatusFilterbycurrentcommunity($tags);

            case 'isCommunity':
                return gdUreIsCommunity($relationalTypeResolver->getID($user)) ? true : null;

            case 'communities':
                // Return only the communities where the user's been accepted as a member
                return gdUreGetCommunities($relationalTypeResolver->getID($user));

            case 'activeCommunities':
                // Return only the communities where the user's been accepted as a member
                return gdUreGetCommunitiesStatusActive($relationalTypeResolver->getID($user));

            case 'hasActiveCommunities':
                $communities = $relationalTypeResolver->resolveValue($resultItem, 'activeCommunities', $variables, $expressions, $options);
                return !empty($communities);
        }

        return parent::resolveValue($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function getFieldTypeResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'communities':
            case 'activeCommunities':
                return UserTypeResolver::class;
        }

        return parent::getFieldTypeResolverClass($relationalTypeResolver, $fieldName);
    }
}

// Static Initialization: Attach
(new GD_UserCommunities_DataLoad_ObjectTypeFieldResolver_Users())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS);
