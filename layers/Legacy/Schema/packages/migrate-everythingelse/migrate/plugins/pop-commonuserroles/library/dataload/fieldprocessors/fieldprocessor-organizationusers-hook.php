<?php
use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Users\TypeResolvers\Object\UserTypeResolver;

class FieldResolver_OrganizationUsers extends AbstractDBDataFieldResolver
{
    use OrganizationFieldResolverTrait;

    public function getClassesToAttachTo(): array
    {
        return array(UserTypeResolver::class);
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

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        $types = [
			'contactPerson' => SchemaDefinition::TYPE_STRING,
            'contactNumber' => SchemaDefinition::TYPE_STRING,
            'organizationtypes' => SchemaDefinition::TYPE_STRING,
            'organizationcategories' => SchemaDefinition::TYPE_STRING,
            'hasOrganizationDetails' => SchemaDefinition::TYPE_BOOL,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?int
    {
        return match($fieldName) {
            'hasOrganizationDetails'
                => SchemaTypeModifiers::NON_NULLABLE,
            'organizationtypes',
            'organizationcategories'
                => SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($relationalTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
			'contactPerson' => $translationAPI->__('', ''),
            'contactNumber' => $translationAPI->__('', ''),
            'organizationtypes' => $translationAPI->__('', ''),
            'organizationcategories' => $translationAPI->__('', ''),
            'hasOrganizationDetails' => $translationAPI->__('', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($relationalTypeResolver, $fieldName);
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
            case 'contactPerson':
                return \PoPSchema\UserMeta\Utils::getUserMeta($relationalTypeResolver->getID($user), GD_URE_METAKEY_PROFILE_CONTACTPERSON, true);

            case 'contactNumber':
                return \PoPSchema\UserMeta\Utils::getUserMeta($relationalTypeResolver->getID($user), GD_URE_METAKEY_PROFILE_CONTACTNUMBER, true);

            case 'organizationtypes':
                return \PoPSchema\UserMeta\Utils::getUserMeta($relationalTypeResolver->getID($user), GD_URE_METAKEY_PROFILE_ORGANIZATIONTYPES);

            case 'organizationcategories':
                return \PoPSchema\UserMeta\Utils::getUserMeta($relationalTypeResolver->getID($user), GD_URE_METAKEY_PROFILE_ORGANIZATIONCATEGORIES);

            case 'hasOrganizationDetails':
                return
                    $relationalTypeResolver->resolveValue($user, 'organizationtypes', $variables, $expressions, $options) ||
                    $relationalTypeResolver->resolveValue($user, 'organizationcategories', $variables, $expressions, $options) ||
                    $relationalTypeResolver->resolveValue($user, 'contactPerson', $variables, $expressions, $options) ||
                    $relationalTypeResolver->resolveValue($user, 'contactNumber', $variables, $expressions, $options);
        }

        return parent::resolveValue($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new FieldResolver_OrganizationUsers())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS);
