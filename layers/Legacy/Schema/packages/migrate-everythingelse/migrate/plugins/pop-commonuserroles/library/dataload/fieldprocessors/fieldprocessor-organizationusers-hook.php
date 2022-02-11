<?php
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\Facades\Translation\TranslationAPIFacade;
use PoPCMSSchema\Users\TypeResolvers\ObjectType\UserObjectTypeResolver;

class ObjectTypeFieldResolver_OrganizationUsers extends AbstractObjectTypeFieldResolver
{
    use OrganizationObjectTypeFieldResolverTrait;

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            UserObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'contactPerson',
            'contactNumber',
            'organizationtypes',
            'organizationcategories',
            'hasOrganizationDetails',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): \PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface
    {
        return match($fieldName) {
			'contactPerson' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'contactNumber' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'organizationtypes' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'organizationcategories' => \PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver::class,
            'hasOrganizationDetails' => \PoP\ComponentModel\TypeResolvers\ScalarType\BooleanScalarTypeResolver::class,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match($fieldName) {
            'hasOrganizationDetails'
                => SchemaTypeModifiers::NON_NULLABLE,
            'organizationtypes',
            'organizationcategories'
                => SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match($fieldName) {
			'contactPerson' => $translationAPI->__('', ''),
            'contactNumber' => $translationAPI->__('', ''),
            'organizationtypes' => $translationAPI->__('', ''),
            'organizationcategories' => $translationAPI->__('', ''),
            'hasOrganizationDetails' => $translationAPI->__('', ''),
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
        array $options = []
    ): mixed {
        $user = $object;
        switch ($fieldName) {
            case 'contactPerson':
                return \PoPCMSSchema\UserMeta\Utils::getUserMeta($objectTypeResolver->getID($user), GD_URE_METAKEY_PROFILE_CONTACTPERSON, true);

            case 'contactNumber':
                return \PoPCMSSchema\UserMeta\Utils::getUserMeta($objectTypeResolver->getID($user), GD_URE_METAKEY_PROFILE_CONTACTNUMBER, true);

            case 'organizationtypes':
                return \PoPCMSSchema\UserMeta\Utils::getUserMeta($objectTypeResolver->getID($user), GD_URE_METAKEY_PROFILE_ORGANIZATIONTYPES);

            case 'organizationcategories':
                return \PoPCMSSchema\UserMeta\Utils::getUserMeta($objectTypeResolver->getID($user), GD_URE_METAKEY_PROFILE_ORGANIZATIONCATEGORIES);

            case 'hasOrganizationDetails':
                return
                    $objectTypeResolver->resolveValue($user, 'organizationtypes', $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options) ||
                    $objectTypeResolver->resolveValue($user, 'organizationcategories', $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options) ||
                    $objectTypeResolver->resolveValue($user, 'contactPerson', $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options) ||
                    $objectTypeResolver->resolveValue($user, 'contactNumber', $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $objectTypeFieldResolutionFeedbackStore, $options);
    }
}

// Static Initialization: Attach
(new ObjectTypeFieldResolver_OrganizationUsers())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::OBJECT_TYPE_FIELD_RESOLVERS);
