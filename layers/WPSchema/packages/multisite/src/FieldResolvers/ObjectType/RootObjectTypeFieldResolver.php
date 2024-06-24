<?php

declare(strict_types=1);

namespace PoPWPSchema\Multisite\FieldResolvers\ObjectType;

use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use WP_Site;

use function get_locale;
// use function get_site_url;

class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?URLScalarTypeResolver $urlScalarTypeResolver = null;

    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        if ($this->stringScalarTypeResolver === null) {
            /** @var StringScalarTypeResolver */
            $stringScalarTypeResolver = $this->instanceManager->getInstance(StringScalarTypeResolver::class);
            $this->stringScalarTypeResolver = $stringScalarTypeResolver;
        }
        return $this->stringScalarTypeResolver;
    }
    final public function setURLScalarTypeResolver(URLScalarTypeResolver $urlScalarTypeResolver): void
    {
        $this->urlScalarTypeResolver = $urlScalarTypeResolver;
    }
    final protected function getURLScalarTypeResolver(): URLScalarTypeResolver
    {
        if ($this->urlScalarTypeResolver === null) {
            /** @var URLScalarTypeResolver */
            $urlScalarTypeResolver = $this->instanceManager->getInstance(URLScalarTypeResolver::class);
            $this->urlScalarTypeResolver = $urlScalarTypeResolver;
        }
        return $this->urlScalarTypeResolver;
    }

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'url',
            'locale',
            'language',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'url' => $this->__('Site\'s URL (in a multisite)', 'multisite'),
            'locale' => $this->__('Site\'s locale (in a multisite)', 'multisite'),
            'language' => $this->__('Site\'s language (in a multisite)', 'multisite'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'url' =>
                $this->getURLScalarTypeResolver(),
            'locale',
            'language' =>
                $this->getStringScalarTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'url',
            'locale',
            'language'
                => SchemaTypeModifiers::NON_NULLABLE,
            default
                => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        /** @var WP_Site */
        $site = $object;
        $siteID = (int) $site->blog_id;
        switch ($fieldDataAccessor->getFieldName()) {
            case 'url':
                /** @var string */
                return $site->siteurl;
                // return get_site_url($siteID);

            case 'locale':
                return $this->getSiteLocale($siteID);

            case 'language':
                $locale = $this->getSiteLocale($siteID);
                $localeParts = explode('_', $locale);
                return $localeParts[0];
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    protected function getSiteLocale(int $siteID): string
    {
        switch_to_blog($siteID);
        $locale = get_locale();
        restore_current_blog();
        return $locale;
    }

    /**
     * Since the return type is known for all the fields in this
     * FieldResolver, there's no need to validate them
     */
    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        return false;
    }
}
