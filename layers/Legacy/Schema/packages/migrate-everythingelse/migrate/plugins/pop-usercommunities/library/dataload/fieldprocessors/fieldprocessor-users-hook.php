<?php
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPSchema\EverythingElse\TypeResolvers\EnumType\MemberPrivilegeEnumTypeResolver;
use PoPSchema\EverythingElse\TypeResolvers\EnumType\MemberStatusEnumTypeResolver;
use PoPSchema\EverythingElse\TypeResolvers\EnumType\MemberTagEnumTypeResolver;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

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

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): \PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'isCommunity'
                => \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver::class,
            'hasActiveCommunities'
                => \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver::class,
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
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match($fieldName) {
            'hasActiveCommunities'
                => SchemaTypeModifiers::NON_NULLABLE,
            'communities',
            'activeCommunities'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match($fieldName) {
            'memberstatus' => $translationAPI->__('', ''),
            'memberprivileges' => $translationAPI->__('', ''),
            'membertags' => $translationAPI->__('', ''),
            'isCommunity' => $translationAPI->__('', ''),
            'communities' => $translationAPI->__('', ''),
            'activeCommunities' => $translationAPI->__('', ''),
            'hasActiveCommunities' => $translationAPI->__('', ''),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed> $variables
     * @param array<string, mixed> $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs,
        array $variables,
        array $expressions,
        \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
        array $options = []
    ): mixed {
        $user = $object;

        switch ($fieldName) {
            case 'memberstatus':
                // All status for all communities
                $status = \PoPCMSSchema\UserMeta\Utils::getUserMeta($objectTypeResolver->getID($user), GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERSTATUS);

                // Filter status for only this community: the logged in user
                return gdUreCommunityMembershipstatusFilterbycurrentcommunity($status);

            case 'memberprivileges':
                // All privileges for all communities
                $privileges = \PoPCMSSchema\UserMeta\Utils::getUserMeta($objectTypeResolver->getID($user), GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERPRIVILEGES);

                // Filter privileges for only this community: the logged in user
                return gdUreCommunityMembershipstatusFilterbycurrentcommunity($privileges);

            case 'membertags':
                // All privileges for all communities
                $tags = \PoPCMSSchema\UserMeta\Utils::getUserMeta($objectTypeResolver->getID($user), GD_URE_METAKEY_PROFILE_COMMUNITIES_MEMBERTAGS);

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
                $communities = $objectTypeResolver->resolveValue($object, 'activeCommunities', $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
                return !empty($communities);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
    }
}

// Static Initialization: Attach
(new GD_UserCommunities_DataLoad_ObjectTypeFieldResolver_Users())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS);
