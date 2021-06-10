<?php
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\FieldResolvers\AbstractFunctionalFieldResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\Schema\TypeCastingHelpers;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;

class GD_UserPlatform_DataLoad_FieldResolver_FunctionalUsers extends AbstractFunctionalFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(UserTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'shortDescriptionFormatted',
            'contactSmall',
            'userPreferences',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'shortDescriptionFormatted' => SchemaDefinition::TYPE_STRING,
            'contactSmall' => SchemaDefinition::TYPE_STRING,
            'userPreferences' => TypeCastingHelpers::makeArray(SchemaDefinition::TYPE_STRING),
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
            'shortDescriptionFormatted' => $translationAPI->__('', ''),
            'contactSmall' => $translationAPI->__('', ''),
            'userPreferences' => $translationAPI->__('', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
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
        $user = $resultItem;
        $cmsapplicationhelpers = \PoP\Application\HelperAPIFactory::getInstance();
        switch ($fieldName) {
            case 'shortDescriptionFormatted':
                // doing esc_html so that single quotes ("'") do not screw the map output
                $value = $typeResolver->resolveValue($user, 'shortDescription', $variables, $expressions, $options);
                return $cmsapplicationhelpers->makeClickable($cmsapplicationhelpers->escapeHTML($value));

            case 'contactSmall':
                $value = array();
                $contacts = $typeResolver->resolveValue($user, 'contact', $variables, $expressions, $options);
                // Remove text, replace all icons with their shorter version
                foreach ($contacts as $contact) {
                    $value[] = array(
                        'tooltip' => $contact['tooltip'],
                        'url' => $contact['url'],
                        'fontawesome' => $contact['fontawesome']
                    );
                }
                return $value;

         // User preferences
            case 'userPreferences':
                return \PoPSchema\UserMeta\Utils::getUserMeta($typeResolver->getID($user), GD_METAKEY_PROFILE_USERPREFERENCES);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new GD_UserPlatform_DataLoad_FieldResolver_FunctionalUsers())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS);
