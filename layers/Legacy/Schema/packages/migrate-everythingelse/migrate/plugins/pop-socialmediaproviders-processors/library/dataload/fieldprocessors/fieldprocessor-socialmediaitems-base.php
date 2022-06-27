<?php
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractObjectTypeFieldResolver;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoPSchema\EverythingElse\TypeResolvers\EnumType\SocialMediaProviderEnumTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

abstract class PoP_SocialMediaProviders_DataLoad_ObjectTypeFieldResolver_FunctionalSocialMediaItems extends AbstractObjectTypeFieldResolver
{
    private ?SocialMediaProviderEnumTypeResolver $socialMediaProviderEnumTypeResolver = null;
    private ?URLScalarTypeResolver $urlScalarTypeResolver = null;

    final public function setSocialMediaProviderEnumTypeResolver(SocialMediaProviderEnumTypeResolver $socialMediaProviderEnumTypeResolver): void
    {
        $this->socialMediaProviderEnumTypeResolver = $socialMediaProviderEnumTypeResolver;
    }
    final protected function getSocialMediaProviderEnumTypeResolver(): SocialMediaProviderEnumTypeResolver
    {
        return $this->socialMediaProviderEnumTypeResolver ??= $this->instanceManager->getInstance(SocialMediaProviderEnumTypeResolver::class);
    }
    final public function setURLScalarTypeResolver(URLScalarTypeResolver $urlScalarTypeResolver): void
    {
        $this->urlScalarTypeResolver = $urlScalarTypeResolver;
    }
    final protected function getURLScalarTypeResolver(): URLScalarTypeResolver
    {
        return $this->urlScalarTypeResolver ??= $this->instanceManager->getInstance(URLScalarTypeResolver::class);
    }

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
			'shareURL' => $this->urlScalarTypeResolver,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match($fieldName) {
			'shareURL' => $this->getTranslationAPI()->__('', ''),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgNameTypeResolvers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        return match ($fieldName) {
            'shareURL' => [
                'provider' => $this->socialMediaProviderEnumTypeResolver,
            ],
            default => parent::getFieldArgNameTypeResolvers($objectTypeResolver, $fieldName),
        };
    }
    
    public function getFieldArgDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): ?string
    {
        return match ([$fieldName => $fieldArgName]) {
            ['shareURL' => 'provider'] => $this->getTranslationAPI()->__('What provider service to get the URL from', ''),
            default => parent::getFieldArgDescription($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }
    
    public function getFieldArgTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): int
    {
        return match ([$fieldName => $fieldArgName]) {
            ['shareURL' => 'provider'] => SchemaTypeModifiers::MANDATORY,
            default => parent::getFieldArgTypeModifiers($objectTypeResolver, $fieldName, $fieldArgName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        \PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface $field,
        \PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        switch ($field->getName()) {
            case 'shareURL':
                $url = $objectTypeResolver->resolveValue($object, 'url', $objectTypeFieldResolutionFeedbackStore);
                if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
                    return $url;
                }
                $title = $objectTypeResolver->resolveValue($object, $this->getTitleField(), $objectTypeFieldResolutionFeedbackStore);
                if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
                    return $title;
                }
                $providerURLs = $this->socialMediaProviderEnumTypeResolver->getEnumValues();
                return $this->getShareUrl($url, $title, $providerURLs[$field->getArgument('provider')?->getValue()]);
        }

        return parent::resolveValue($objectTypeResolver, $object, $field, $objectTypeFieldResolutionFeedbackStore);
    }
}
