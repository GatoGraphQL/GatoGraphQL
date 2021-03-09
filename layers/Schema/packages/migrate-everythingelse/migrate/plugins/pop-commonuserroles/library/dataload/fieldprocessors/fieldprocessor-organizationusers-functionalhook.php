<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\FieldResolvers\AbstractFunctionalFieldResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;

class GD_URE_Custom_DataLoad_FieldResolver_FunctionalOrganizationUsers extends AbstractFunctionalFieldResolver
{
    use OrganizationFieldResolverTrait;

    public function getClassesToAttachTo(): array
    {
        return array(UserTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
			'organizationTypesByName',
            'organizationCategoriesByName',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $types = [
			'organizationTypesByName' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
            'organizationCategoriesByName' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
			'organizationTypesByName' => $translationAPI->__('', ''),
            'organizationCategoriesByName' => $translationAPI->__('', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     * @return mixed
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ) {
        $user = $resultItem;
        switch ($fieldName) {
            case 'organizationTypesByName':
                $selected = $typeResolver->resolveValue($user, 'organizationtypes', $variables, $expressions, $options);
                $params = array(
                    'selected' => $selected
                );
                $organizationtypes = new GD_FormInput_OrganizationTypes($params);
                return $organizationtypes->getSelectedValue();

            case 'organizationCategoriesByName':
                $selected = $typeResolver->resolveValue($user, 'organizationcategories', $variables, $expressions, $options);
                $params = array(
                    'selected' => $selected
                );
                $organizationcategories = new GD_FormInput_OrganizationCategories($params);
                return $organizationcategories->getSelectedValue();
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new GD_URE_Custom_DataLoad_FieldResolver_FunctionalOrganizationUsers())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS);
