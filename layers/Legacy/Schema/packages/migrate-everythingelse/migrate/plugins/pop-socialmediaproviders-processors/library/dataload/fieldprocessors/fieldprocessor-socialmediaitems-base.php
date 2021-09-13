<?php
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoPSchema\EverythingElse\Enums\SocialMediaProviderEnum;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;

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

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        $types = [
			'shareURL' => SchemaDefinition::TYPE_URL,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        $translationAPI = TranslationAPIFacade::getInstance();
        $descriptions = [
			'shareURL' => $translationAPI->__('', ''),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($relationalTypeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($relationalTypeResolver, $fieldName);
        $translationAPI = TranslationAPIFacade::getInstance();
        $instanceManager = InstanceManagerFacade::getInstance();
        switch ($fieldName) {
            case 'shareURL':
                /**
                 * @var SocialMediaProviderEnum
                 */
                $socialMediaProviderEnum = $instanceManager->getInstance(SocialMediaProviderEnum::class);
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'provider',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ENUM,
                            SchemaDefinition::ARGNAME_ENUM_NAME => $socialMediaProviderEnum->getName(),
                            SchemaDefinition::ARGNAME_ENUM_VALUES => SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions(
                                array_keys($socialMediaProviderEnum->getValues())
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
        RelationalTypeResolverInterface $relationalTypeResolver,
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
                $url = $relationalTypeResolver->resolveValue($object, 'url', $variables, $expressions, $options);
                if (GeneralUtils::isError($url)) {
                    return $url;
                }
                $title = $relationalTypeResolver->resolveValue($object, $this->getTitleField(), $variables, $expressions, $options);
                if (GeneralUtils::isError($title)) {
                    return $title;
                }
                /**
                 * @var SocialMediaProviderEnum
                 */
                $socialMediaProviderEnum = $instanceManager->getInstance(SocialMediaProviderEnum::class);
                $providerURLs = $socialMediaProviderEnum->getValues();
                return $this->getShareUrl($url, $title, $providerURLs[$fieldArgs['provider']]);
        }

        return parent::resolveValue($relationalTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
