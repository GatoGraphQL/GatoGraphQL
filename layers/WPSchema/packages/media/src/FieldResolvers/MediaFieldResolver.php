<?php

declare(strict_types=1);

namespace PoPWPSchema\Media\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\CMS\CMSHelperServiceInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Engine\Formatters\DateFormatterInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\Media\TypeResolvers\MediaTypeResolver;
use PoPSchema\QueriedObject\FieldInterfaceResolvers\QueryableFieldInterfaceResolver;
use WP_Post;

class MediaFieldResolver extends AbstractQueryableFieldResolver
{
    public function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        NameResolverInterface $nameResolver,
        CMSServiceInterface $cmsService,
        SemverHelperServiceInterface $semverHelperService,
        protected CMSHelperServiceInterface $cmsHelperService,
        protected DateFormatterInterface $dateFormatter
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
            $instanceManager,
            $fieldQueryInterpreter,
            $nameResolver,
            $cmsService,
            $semverHelperService,
        );
    }

    public function getClassesToAttachTo(): array
    {
        return array(MediaTypeResolver::class);
    }

    public function getImplementedFieldInterfaceResolverClasses(): array
    {
        return [
            QueryableFieldInterfaceResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'url',
            'urlPath',
            'slug',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'url' => SchemaDefinition::TYPE_URL,
            'urlPath' => SchemaDefinition::TYPE_STRING,
            'slug' => SchemaDefinition::TYPE_STRING,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        $nonNullableFieldNames = [
            'url',
            'urlPath',
            'slug',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return SchemaTypeModifiers::NON_NULLABLE;
        }
        return parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'url' => $this->translationAPI->__('Media element URL', 'pop-media'),
            'urlPath' => $this->translationAPI->__('Media element URL path', 'pop-media'),
            'slug' => $this->translationAPI->__('Media element slug', 'pop-media'),
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
        /** @var WP_Post */
        $mediaItem = $resultItem;
        switch ($fieldName) {
            case 'url':
            case 'urlPath':
                $url = \get_permalink($mediaItem->ID);
                if ($fieldName === 'url') {
                    return $url;
                }
                /** @var string */
                return $this->cmsHelperService->getLocalURLPath($url);
            case 'slug':
                return $mediaItem->post_name;
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
