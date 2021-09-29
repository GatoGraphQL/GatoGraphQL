<?php

declare(strict_types=1);

namespace PoPSchema\Pages\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoPSchema\CustomPosts\ModuleProcessors\CommonCustomPostFilterInputContainerModuleProcessor;
use PoPSchema\Pages\ComponentConfiguration;
use PoPSchema\Pages\ModuleProcessors\PageFilterInputContainerModuleProcessor;
use PoPSchema\Pages\TypeAPIs\PageTypeAPIInterface;
use PoPSchema\Pages\TypeResolvers\ObjectType\PageObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\ModuleProcessors\CommonFilterInputContainerModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use Symfony\Contracts\Service\Attribute\Required;

class RootPageObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    protected IntScalarTypeResolver $intScalarTypeResolver;
    protected PageObjectTypeResolver $pageObjectTypeResolver;
    protected PageTypeAPIInterface $pageTypeAPI;

    #[Required]
    public function autowireRootPageObjectTypeFieldResolver(
        IntScalarTypeResolver $intScalarTypeResolver,
        PageObjectTypeResolver $pageObjectTypeResolver,
        PageTypeAPIInterface $pageTypeAPI,
    ): void {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
        $this->pageObjectTypeResolver = $pageObjectTypeResolver;
        $this->pageTypeAPI = $pageTypeAPI;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'page',
            'pageBySlug',
            'pages',
            'pageCount',
            'pageForAdmin',
            'pageBySlugForAdmin',
            'pagesForAdmin',
            'pageCountForAdmin',
        ];
    }

    public function getAdminFieldNames(): array
    {
        return [
            'pageForAdmin',
            'pageBySlugForAdmin',
            'pagesForAdmin',
            'pageCountForAdmin',
        ];
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'page' => $this->translationAPI->__('Page with a specific ID', 'pages'),
            'pageBySlug' => $this->translationAPI->__('Page with a specific slug', 'pages'),
            'pages' => $this->translationAPI->__('Pages', 'pages'),
            'pageCount' => $this->translationAPI->__('Number of pages', 'pages'),
            'pageForAdmin' => $this->translationAPI->__('[Unrestricted] Page with a specific ID', 'pages'),
            'pageBySlugForAdmin' => $this->translationAPI->__('[Unrestricted] Page with a specific slug', 'pages'),
            'pagesForAdmin' => $this->translationAPI->__('[Unrestricted] Pages', 'pages'),
            'pageCountForAdmin' => $this->translationAPI->__('[Unrestricted] Number of pages', 'pages'),
            default => parent::getSchemaFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'page':
            case 'pageBySlug':
            case 'pages':
            case 'pageForAdmin':
            case 'pageBySlugForAdmin':
            case 'pagesForAdmin':
                return $this->pageObjectTypeResolver;
        }
        return match ($fieldName) {
            'pageCount' => $this->intScalarTypeResolver,
            'pageCountForAdmin' => $this->intScalarTypeResolver,
            default => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'pageCount',
            'pageCountForAdmin'
                => SchemaTypeModifiers::NON_NULLABLE,
            'pages',
            'pagesForAdmin'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldFilterInputContainerModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'pages' => [
                PageFilterInputContainerModuleProcessor::class,
                PageFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_PAGELISTLIST
            ],
            'pageCount' => [
                PageFilterInputContainerModuleProcessor::class,
                PageFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_PAGELISTCOUNT
            ],
            'pagesForAdmin' => [
                PageFilterInputContainerModuleProcessor::class,
                PageFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINPAGELISTLIST
            ],
            'pageCountForAdmin' => [
                PageFilterInputContainerModuleProcessor::class,
                PageFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINPAGELISTCOUNT
            ],
            'page' => [
                CommonFilterInputContainerModuleProcessor::class,
                CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_ID
            ],
            'pageForAdmin' => [
                CommonCustomPostFilterInputContainerModuleProcessor::class,
                CommonCustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_ID_STATUS
            ],
            'pageBySlug' => [
                CommonFilterInputContainerModuleProcessor::class,
                CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_SLUG
            ],
            'pageBySlugForAdmin' => [
                CommonCustomPostFilterInputContainerModuleProcessor::class,
                CommonCustomPostFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_BY_SLUG_STATUS
            ],
            default => parent::getFieldFilterInputContainerModule($objectTypeResolver, $fieldName),
        };
    }

    protected function getFieldFilterInputDefaultValues(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'pages':
            case 'pagesForAdmin':
                $limitFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_LIMIT
                ]);
                return [
                    $limitFilterInputName => ComponentConfiguration::getPageListDefaultLimit(),
                ];
        }
        return parent::getFieldFilterInputDefaultValues($objectTypeResolver, $fieldName);
    }

    /**
     * Validate the constraints for a field argument
     *
     * @return string[] Error messages
     */
    public function validateFieldArgument(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        $errors = parent::validateFieldArgument(
            $objectTypeResolver,
            $fieldName,
            $fieldArgName,
            $fieldArgValue,
        );

        // Check the "limit" fieldArg
        switch ($fieldName) {
            case 'pages':
            case 'pagesForAdmin':
                if (
                    $maybeError = $this->maybeValidateLimitFieldArgument(
                        ComponentConfiguration::getPageListMaxLimit(),
                        $fieldName,
                        $fieldArgName,
                        $fieldArgValue
                    )
                ) {
                    $errors[] = $maybeError;
                }
                break;
        }
        return $errors;
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
        $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldName, $fieldArgs);
        switch ($fieldName) {
            case 'page':
            case 'pageBySlug':
            case 'pageForAdmin':
            case 'pageBySlugForAdmin':
                if ($pages = $this->pageTypeAPI->getPages($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $pages[0];
                }
                return null;
            case 'pages':
            case 'pagesForAdmin':
                return $this->pageTypeAPI->getPages($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'pageCount':
            case 'pageCountForAdmin':
                return $this->pageTypeAPI->getPageCount($query);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
