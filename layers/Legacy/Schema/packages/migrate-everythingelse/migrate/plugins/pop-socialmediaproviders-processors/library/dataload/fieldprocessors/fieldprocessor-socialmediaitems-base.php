<?php
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\EverythingElse\TypeResolvers\EnumType\SocialMediaProviderEnumTypeResolver;

abstract class PoP_SocialMediaProviders_DataLoad_ObjectTypeFieldResolver_FunctionalSocialMediaItems extends AbstractObjectTypeFieldResolver
{
    protected function getShareUrl($url, $title, $provider)
    {
        $settings = gdSocialmediaProviderSettings();
        $provider_url = $settings[$provider]['shareURL'];
        return str_replace(array('%url%', '%title%'), array(rawurlencode($url), rawurlencode($title)), $provider_url);
    }

    protected function getTitleField()
    {
        return null;
    }

    public function getFieldNamesToResolve(): array
    {
        return [
			'shareURL',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): \PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface
    {
        return match($fieldName) {
			'shareURL' => \PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver::class,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        return match($fieldName) {
			'shareURL' => $translationAPI->__('', ''),
            default => parent::getSchemaFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldArgNameResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            default => parent::getSchemaFieldArgNameResolvers($objectTypeResolver, $fieldName),
        };
    }
    
    public function getSchemaFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            default => parent::getSchemaFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }
    
    public function getSchemaFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        return match ([$fieldName => $fieldArgName]) {
            default => parent::getSchemaFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }
    
    public function getSchemaFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?int
    {
        return match ([$fieldName => $fieldArgName]) {
            default => parent::getSchemaFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function getSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($objectTypeResolver, $fieldName);
        $translationAPI = TranslationAPIFacade::getInstance();
        $instanceManager = InstanceManagerFacade::getInstance();
        switch ($fieldName) {
            case 'shareURL':
                /**
                 * @var SocialMediaProviderEnumTypeResolver
                 */
                $socialMediaProviderEnumTypeResolver = $instanceManager->getInstance(SocialMediaProviderEnumTypeResolver::class);
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'provider',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ENUM,
                            SchemaDefinition::ARGNAME_ENUM_NAME => $socialMediaProviderEnumTypeResolver->getTypeName(),
                            SchemaDefinition::ARGNAME_ENUM_VALUES => SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions(
                                $socialMediaProviderEnumTypeResolver
                            ),
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('What provider service to get the URL from', ''),
                            SchemaDefinition::ARGNAME_MANDATORY => true,
                        ],
                    ]
                );
        }

        return $schemaFieldArgs;
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
        $instanceManager = InstanceManagerFacade::getInstance();
        switch ($fieldName) {
            case 'shareURL':
                $url = $objectTypeResolver->resolveValue($object, 'url', $variables, $expressions, $options);
                if (GeneralUtils::isError($url)) {
                    return $url;
                }
                $title = $objectTypeResolver->resolveValue($object, $this->getTitleField(), $variables, $expressions, $options);
                if (GeneralUtils::isError($title)) {
                    return $title;
                }
                /**
                 * @var SocialMediaProviderEnumTypeResolver
                 */
                $socialMediaProviderEnumTypeResolver = $instanceManager->getInstance(SocialMediaProviderEnumTypeResolver::class);
                $providerURLs = $socialMediaProviderEnumTypeResolver->getEnumValues();
                return $this->getShareUrl($url, $title, $providerURLs[$fieldArgs['provider']]);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
