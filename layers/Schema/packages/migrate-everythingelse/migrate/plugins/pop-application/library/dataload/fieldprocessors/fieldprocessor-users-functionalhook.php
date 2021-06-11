<?php
use PoP\ComponentModel\FieldResolvers\AbstractFunctionalFieldResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\Users\Facades\UserTypeAPIFacade;
use PoPSchema\Users\TypeResolvers\UserTypeResolver;

class PoP_Application_DataLoad_FieldResolver_FunctionalUsers extends AbstractFunctionalFieldResolver
{
    public function getClassesToAttachTo(): array
    {
        return array(
            UserTypeResolver::class,
        );
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'multilayoutKeys',
            'mentionQueryby',
            'descriptionFormatted',
            'excerpt',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
			'multilayoutKeys' => SchemaDefinition::TYPE_STRING,
            'mentionQueryby' => SchemaDefinition::TYPE_STRING,
            'descriptionFormatted' => SchemaDefinition::TYPE_STRING,
            'excerpt' => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match($fieldName) {
            'multilayoutKeys' => SchemaTypeModifiers::IS_ARRAY,
            default => parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
			'multilayoutKeys' => $translationAPI->__('', ''),
            'mentionQueryby' => $translationAPI->__('', ''),
            'descriptionFormatted' => $translationAPI->__('', ''),
            'excerpt' => $translationAPI->__('', ''),
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
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        $cmsapplicationhelpers = \PoP\Application\HelperAPIFactory::getInstance();
        switch ($fieldName) {
            case 'multilayoutKeys':
                return array(
                    $typeResolver->resolveValue($user, 'role', $variables, $expressions, $options),
                );

             // Needed for tinyMCE-mention plug-in
            case 'mentionQueryby':
                return $typeResolver->resolveValue($user, 'displayName', $variables, $expressions, $options);

            case 'descriptionFormatted':
                $value = $typeResolver->resolveValue($user, 'description', $variables, $expressions, $options);
                return $cmsapplicationhelpers->makeClickable($cmsapplicationhelpers->convertLinebreaksToHTML(strip_tags($value)));

            case 'excerpt':
                $readmore = sprintf(
                    TranslationAPIFacade::getInstance()->__('... <a href="%s">Read more</a>', 'pop-application'),
                    $userTypeAPI->getUserURL($typeResolver->getID($user))
                );
                // Excerpt length can be set through fieldArgs
                $length = $fieldArgs['length'] ? (int) $fieldArgs['length'] : 300;
                return $cmsapplicationhelpers->makeClickable(limitString(strip_tags($cmsapplicationhelpers->convertLinebreaksToHTML($userTypeAPI->getUserDescription($typeResolver->getID($user)))), $length, $readmore));
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}

// Static Initialization: Attach
(new PoP_Application_DataLoad_FieldResolver_FunctionalUsers())->attach(\PoP\ComponentModel\AttachableExtensions\AttachableExtensionGroups::FIELDRESOLVERS);
