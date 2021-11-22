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

class RootPageObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?PageObjectTypeResolver $pageObjectTypeResolver = null;
    private ?PageTypeAPIInterface $pageTypeAPI = null;

    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    final public function setPageObjectTypeResolver(PageObjectTypeResolver $pageObjectTypeResolver): void
    {
        $this->pageObjectTypeResolver = $pageObjectTypeResolver;
    }
    final protected function getPageObjectTypeResolver(): PageObjectTypeResolver
    {
        return $this->pageObjectTypeResolver ??= $this->instanceManager->getInstance(PageObjectTypeResolver::class);
    }
    final public function setPageTypeAPI(PageTypeAPIInterface $pageTypeAPI): void
    {
        $this->pageTypeAPI = $pageTypeAPI;
    }
    final protected function getPageTypeAPI(): PageTypeAPIInterface
    {
        return $this->pageTypeAPI ??= $this->instanceManager->getInstance(PageTypeAPIInterface::class);
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
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'page' => $this->getTranslationAPI()->__('Page with a specific ID', 'pages'),
            'pageBySlug' => $this->getTranslationAPI()->__('Page with a specific slug', 'pages'),
            'pages' => $this->getTranslationAPI()->__('Pages', 'pages'),
            'pageCount' => $this->getTranslationAPI()->__('Number of pages', 'pages'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'page',
            'pageBySlug',
            'pages'
                => $this->getPageObjectTypeResolver(),
            'pageCount'
                => $this->getIntScalarTypeResolver(),
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'pageCount' => SchemaTypeModifiers::NON_NULLABLE,
            'pages' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
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
            'page' => [
                CommonFilterInputContainerModuleProcessor::class,
                CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_ID
            ],
            'pageBySlug' => [
                CommonFilterInputContainerModuleProcessor::class,
                CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_SLUG
            ],
            default => parent::getFieldFilterInputContainerModule($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        switch ($fieldName) {
            case 'pages':
                $limitFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_LIMIT
                ]);
                if ($fieldArgName === $limitFilterInputName) {
                    return ComponentConfiguration::getPageListDefaultLimit();
                }
                break;
        }
        return parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
    }

    /**
     * Validate the constraints for a field argument
     *
     * @return string[] Error messages
     */
    public function validateFieldArgValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        $errors = parent::validateFieldArgValue(
            $objectTypeResolver,
            $fieldName,
            $fieldArgName,
            $fieldArgValue,
        );

        // Check the "limit" fieldArg
        switch ($fieldName) {
            case 'pages':
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
        array $fieldArgs,
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldName, $fieldArgs);
        switch ($fieldName) {
            case 'page':
            case 'pageBySlug':
                if ($pages = $this->getPageTypeAPI()->getPages($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $pages[0];
                }
                return null;
            case 'pages':
                return $this->getPageTypeAPI()->getPages($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'pageCount':
                return $this->getPageTypeAPI()->getPageCount($query);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
