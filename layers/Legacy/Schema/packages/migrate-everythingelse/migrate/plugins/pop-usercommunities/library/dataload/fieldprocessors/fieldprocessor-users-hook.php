<?php
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\EverythingElse\Enums\MemberPrivilegeEnum;
use PoPSchema\EverythingElse\Enums\MemberTagEnum;
use PoPSchema\EverythingElse\Enums\TypeResolver;
use PoPSchema\EverythingElse\TypeResolvers\EnumType\MemberPrivilegeEnumTypeResolver;
use PoPSchema\EverythingElse\TypeResolvers\EnumType\MemberStatusEnumTypeResolver;
use PoPSchema\EverythingElse\TypeResolvers\EnumType\MemberTagEnumTypeResolver;
use PoPSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class GD_UserCommunities_DataLoad_ObjectTypeFieldResolver_Users extends AbstractObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserObjectTypeResolver::class,
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

    public function getFieldTypeResolverClass(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string
    {
        $types = [
            'isCommunity' => \PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver::class,
            'hasActiveCommunities' => \PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver::class,
        ];
        return $types[$fieldName] ?? parent::getFieldTypeResolverClass($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match($fieldName) {
            'hasActiveCommunities'
                => SchemaTypeModifiers::NON_NULLABLE,
            'communities',
            'activeCommunities'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
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
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    public function getFieldTypeResolverClass(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): string
    {
        return match ($fieldName) {
            'communities',
            'activeCommunities'
                => UserObjectTypeResolver::class,
            'memberstatus'
                => MemberStatusEnumTypeResolver::class,
            'memberprivileges'
                => MemberPrivilegeEnumTypeResolver::class,
            'membertags'
                => MemberTagEnumTypeResolver::class,
            default
                => parent::getFieldTypeResolverClass($objectTypeResolver, $fieldName),
        };
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
        $user = $object;

        switch ($fieldName) {
            case 'memberstatus':
                // All status for all communities
                $status = \PoPSchema\UserMeta\Utils::getUserMeta($objectTypeResolver->getID($user), GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS);

                // Filter status for only this community: the logged in user
                return gdUreCommunityMembershipstatusFilterbycurrentcommunity($status);

            case 'memberprivileges':
                // All privileges for all communities
                $privileges = \PoPSchema\UserMeta\Utils::getUserMeta($objectTypeResolver->getID($user), GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERPRIVILEGES);

                // Filter privileges for only this community: the logged in user
                return gdUreCommunityMembershipstatusFilterbycurrentcommunity($privileges);

            case 'membertags':
                // All privileges for all communities
                $tags = \PoPSchema\UserMeta\Utils::getUserMeta($objectTypeResolver->getID($user), GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERTAGS);

                // Filter privileges for only this community: the logged in user
                return gdUreCommunityMembershipstatusFilterbycurrentcommunity($tags);

            case 'isCommunity':
                return gdUreIsCommunity($objectTypeResolver->getID($user)) ? true : null;

            case 'communities':
                // Return only the communities where the user's been accepted as a member
                return gdUreGetCommunities($objectTypeResolver->getID($user));

            case 'activeCommunities':
                // Return only the communities where the user's been accepted as a member
                return gdUreGetCommunitiesStatusActive($objectTypeResolver->getID($user));

            case 'hasActiveCommunities':
                $communities = $objectTypeResolver->resolveValue($object, 'activeCommunities', $variables, $expressions, $options);
                return !empty($communities);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new GD_UserCommunities_DataLoad_ObjectTypeFieldResolver_Users())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS);
