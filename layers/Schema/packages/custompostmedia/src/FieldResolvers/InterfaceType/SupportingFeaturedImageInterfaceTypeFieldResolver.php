<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMedia\FieldResolvers\InterfaceType;

use PoP\ComponentModel\FieldResolvers\InterfaceType\AbstractInterfaceTypeFieldResolver;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Registries\TypeRegistryInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Schema\SchemaNamespacingServiceInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\CustomPostMedia\TypeResolvers\InterfaceType\SupportingFeaturedImageInterfaceTypeResolver;
use PoPSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver;

class SupportingFeaturedImageInterfaceTypeFieldResolver extends AbstractInterfaceTypeFieldResolver
{
    public function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        NameResolverInterface $nameResolver,
        CMSServiceInterface $cmsService,
        SchemaNamespacingServiceInterface $schemaNamespacingService,
        TypeRegistryInterface $typeRegistry,
        SchemaDefinitionServiceInterface $schemaDefinitionService,
        protected BooleanScalarTypeResolver $booleanScalarTypeResolver,
        protected IDScalarTypeResolver $idScalarTypeResolver,
        protected MediaObjectTypeResolver $mediaObjectTypeResolver,
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
            $instanceManager,
            $nameResolver,
            $cmsService,
            $schemaNamespacingService,
            $typeRegistry,
            $schemaDefinitionService,
        );
    }

    public function getInterfaceTypeResolverClassesToAttachTo(): array
    {
        return [
            SupportingFeaturedImageInterfaceTypeResolver::class,
        ];
    }

    public function getFieldNamesToImplement(): array
    {
        return [
            'hasFeaturedImage',
            'featuredImage',
        ];
    }

    public function getFieldTypeResolver(string $fieldName): ConcreteTypeResolverInterface
    {
        $types = [
            'featuredImage' => $this->mediaObjectTypeResolver,
            'hasFeaturedImage' => $this->booleanScalarTypeResolver,
        ];
        return $types[$fieldName] ?? parent::getFieldTypeResolver($fieldName);
    }

    public function getSchemaFieldTypeModifiers(string $fieldName): ?int
    {
        $nonNullableFieldNames = [
            'hasFeaturedImage',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getSchemaFieldTypeModifiers($fieldName);
    }

    public function getSchemaFieldDescription(string $fieldName): ?string
    {
        $descriptions = [
            'hasFeaturedImage' => $this->translationAPI->__('Does the custom post have a featured image?', 'custompostmedia'),
            'featuredImage' => $this->translationAPI->__('Featured image from the custom post', 'custompostmedia'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($fieldName);
    }
}
