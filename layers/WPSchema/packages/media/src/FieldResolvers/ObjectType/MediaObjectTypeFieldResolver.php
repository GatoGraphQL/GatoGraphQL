<?php

declare(strict_types=1);

namespace PoPWPSchema\Media\FieldResolvers\ObjectType;

use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Engine\EngineInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\CMS\CMSHelperServiceInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Engine\Formatters\DateFormatterInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver;
use PoPSchema\QueriedObject\FieldResolvers\InterfaceType\QueryableInterfaceTypeFieldResolver;
use WP_Post;

class MediaObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    public function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        NameResolverInterface $nameResolver,
        CMSServiceInterface $cmsService,
        SemverHelperServiceInterface $semverHelperService,
        SchemaDefinitionServiceInterface $schemaDefinitionService,
        EngineInterface $engine,
        ModuleProcessorManagerInterface $moduleProcessorManager,
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
            $schemaDefinitionService,
            $engine,
            $moduleProcessorManager,
        );
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            MediaObjectTypeResolver::class,
        ];
    }

    public function getImplementedInterfaceTypeFieldResolverClasses(): array
    {
        return [
            QueryableInterfaceTypeFieldResolver::class,
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

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'url' => $this->translationAPI->__('Media element URL', 'pop-media'),
            'urlPath' => $this->translationAPI->__('Media element URL path', 'pop-media'),
            'slug' => $this->translationAPI->__('Media element slug', 'pop-media'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
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
        /** @var WP_Post */
        $mediaItem = $object;
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

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
