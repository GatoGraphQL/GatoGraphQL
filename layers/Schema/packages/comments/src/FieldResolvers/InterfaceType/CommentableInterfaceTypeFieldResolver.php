<?php

declare(strict_types=1);

namespace PoPSchema\Comments\FieldResolvers\InterfaceType;

use PoP\ComponentModel\FieldResolvers\InterfaceType\AbstractQueryableSchemaInterfaceTypeFieldResolver;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoPSchema\Comments\ComponentConfiguration;
use PoPSchema\Comments\ModuleProcessors\CommentFilterInputContainerModuleProcessor;
use PoPSchema\Comments\TypeResolvers\InterfaceType\CommentableInterfaceTypeResolver;
use PoPSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPSchema\SchemaCommons\FormInputs\OrderFormInput;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;

class CommentableInterfaceTypeFieldResolver extends AbstractQueryableSchemaInterfaceTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    private ?BooleanScalarTypeResolver $booleanScalarTypeResolver = null;
    private ?IntScalarTypeResolver $intScalarTypeResolver = null;
    private ?CommentObjectTypeResolver $commentObjectTypeResolver = null;

    final public function setBooleanScalarTypeResolver(BooleanScalarTypeResolver $booleanScalarTypeResolver): void
    {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
    }
    final protected function getBooleanScalarTypeResolver(): BooleanScalarTypeResolver
    {
        return $this->booleanScalarTypeResolver ??= $this->instanceManager->getInstance(BooleanScalarTypeResolver::class);
    }
    final public function setIntScalarTypeResolver(IntScalarTypeResolver $intScalarTypeResolver): void
    {
        $this->intScalarTypeResolver = $intScalarTypeResolver;
    }
    final protected function getIntScalarTypeResolver(): IntScalarTypeResolver
    {
        return $this->intScalarTypeResolver ??= $this->instanceManager->getInstance(IntScalarTypeResolver::class);
    }
    final public function setCommentObjectTypeResolver(CommentObjectTypeResolver $commentObjectTypeResolver): void
    {
        $this->commentObjectTypeResolver = $commentObjectTypeResolver;
    }
    final protected function getCommentObjectTypeResolver(): CommentObjectTypeResolver
    {
        return $this->commentObjectTypeResolver ??= $this->instanceManager->getInstance(CommentObjectTypeResolver::class);
    }

    public function getInterfaceTypeResolverClassesToAttachTo(): array
    {
        return [
            CommentableInterfaceTypeResolver::class,
        ];
    }

    public function getFieldNamesToImplement(): array
    {
        return [
            'areCommentsOpen',
            'hasComments',
            'commentCount',
            'comments',
        ];
    }

    public function getFieldTypeResolver(string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'comments' => $this->getCommentObjectTypeResolver(),
            'areCommentsOpen' => $this->getBooleanScalarTypeResolver(),
            'hasComments' => $this->getBooleanScalarTypeResolver(),
            'commentCount' => $this->getIntScalarTypeResolver(),
            default => parent::getFieldTypeResolver($fieldName),
        };
    }

    public function getFieldTypeModifiers(string $fieldName): int
    {
        return match ($fieldName) {
            'areCommentsOpen',
            'hasComments',
            'commentCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'comments'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getFieldTypeModifiers($fieldName),
        };
    }

    public function getFieldDescription(string $fieldName): ?string
    {
        return match ($fieldName) {
            'areCommentsOpen' => $this->getTranslationAPI()->__('Are comments open to be added to the custom post', 'pop-comments'),
            'hasComments' => $this->getTranslationAPI()->__('Does the custom post have comments?', 'pop-comments'),
            'commentCount' => $this->getTranslationAPI()->__('Number of comments added to the custom post', 'pop-comments'),
            'comments' => $this->getTranslationAPI()->__('Comments added to the custom post', 'pop-comments'),
            default => parent::getFieldDescription($fieldName),
        };
    }

    public function getFieldFilterInputContainerModule(string $fieldName): ?array
    {
        return match ($fieldName) {
            'comments' => [
                CommentFilterInputContainerModuleProcessor::class,
                CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTS
            ],
            'commentCount' => [
                CommentFilterInputContainerModuleProcessor::class,
                CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_COMMENTCOUNT
            ],
            default => parent::getFieldFilterInputContainerModule($fieldName),
        };
    }

    public function getFieldArgDefaultValue(string $fieldName, string $fieldArgName): mixed
    {
        switch ($fieldName) {
            case 'comments':
            case 'commentCount':
                // By default retrieve the top level comments (with ID => 0)
                $parentIDFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_PARENT_ID
                ]);
                if ($fieldArgName === $parentIDFilterInputName) {
                    return 0;
                }
                break;
        }
        switch ($fieldName) {
            case 'comments':
                $limitFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_LIMIT
                ]);
                if ($fieldArgName === $limitFilterInputName) {
                    return ComponentConfiguration::getCustomPostCommentOrCommentResponseListDefaultLimit();
                }
                $orderFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_SORT
                ]);
                if ($fieldArgName === $orderFilterInputName) {
                    // Order by descending date
                    $orderBy = $this->getNameResolver()->getName('popcms:dbcolumn:orderby:comments:date');
                    $order = 'DESC';
                    return $orderBy . OrderFormInput::SEPARATOR . $order;
                }
                break;
        }
        return parent::getFieldArgDefaultValue($fieldName, $fieldArgName);
    }

    /**
     * Validate the constraints for a field argument
     *
     * @return string[] Error messages
     */
    public function validateFieldArgValue(
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        $errors = parent::validateFieldArgValue(
            $fieldName,
            $fieldArgName,
            $fieldArgValue,
        );

        // Check the "limit" fieldArg
        switch ($fieldName) {
            case 'comments':
                if (
                    $maybeError = $this->maybeValidateLimitFieldArgument(
                        ComponentConfiguration::getCommentListMaxLimit(),
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
}
