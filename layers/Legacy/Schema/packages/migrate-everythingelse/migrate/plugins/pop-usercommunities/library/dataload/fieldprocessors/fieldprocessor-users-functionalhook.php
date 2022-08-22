<?php
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class GD_UserCommunities_DataLoad_ObjectTypeFieldResolver_FunctionalUsers extends AbstractObjectTypeFieldResolver
{
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'editMembershipURL',
            'editMemberStatusInlineURL',
            'memberStatusByName',
            'memberPrivilegesByName',
            'memberTagsByName',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): \PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface
    {
        return match($fieldName) {
			'editMembershipURL' => \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver::class,
            'editMemberStatusInlineURL' => \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver::class,
            'memberStatusByName' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'memberPrivilegesByName' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'memberTagsByName' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match($fieldName) {
            'memberStatusByName',
            'memberPrivilegesByName',
            'memberTagsByName'
                => SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match($fieldName) {
			'editMembershipURL' => $translationAPI->__('', ''),
            'editMemberStatusInlineURL' => $translationAPI->__('', ''),
            'memberStatusByName' => $translationAPI->__('', ''),
            'memberPrivilegesByName' => $translationAPI->__('', ''),
            'memberTagsByName' => $translationAPI->__('', ''),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        \PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface $fieldDataAccessor,
        \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $user = $object;
        switch ($field->getName()) {
            case 'editMembershipURL':
                return gdUreEditMembershipUrl($objectTypeResolver->getID($user));

            case 'editMemberStatusInlineURL':
                return gdUreEditMembershipUrl($objectTypeResolver->getID($user), true);

            case 'memberStatusByName':
                $selected = $objectTypeResolver->resolveValue($user, 'memberstatus', $objectTypeFieldResolutionFeedbackStore);
                $status = new GD_URE_FormInput_MultiMemberStatus('', $selected);
                return $status->getSelectedValue();

            case 'memberPrivilegesByName':
                $selected = $objectTypeResolver->resolveValue($user, 'memberprivileges', $objectTypeFieldResolutionFeedbackStore);
                $privileges = new GD_URE_FormInput_FilterMemberPrivileges('', $selected);
                return $privileges->getSelectedValue();

            case 'memberTagsByName':
                $selected = $objectTypeResolver->resolveValue($user, 'membertags', $objectTypeFieldResolutionFeedbackStore);
                $tags = new GD_URE_FormInput_FilterMemberTags('', $selected);
                return $tags->getSelectedValue();
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }
}

// Static Initialization: Attach
(new GD_UserCommunities_DataLoad_ObjectTypeFieldResolver_FunctionalUsers())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS);
