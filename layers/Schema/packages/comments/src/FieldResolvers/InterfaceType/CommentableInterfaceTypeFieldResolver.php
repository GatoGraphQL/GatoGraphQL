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
use Symfony\Contracts\Service\Attribute\Required;

class CommentableInterfaceTypeFieldResolver extends AbstractQueryableSchemaInterfaceTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    protected BooleanScalarTypeResolver $booleanScalarTypeResolver;
    protected IntScalarTypeResolver $intScalarTypeResolver;
    protected CommentObjectTypeResolver $commentObjectTypeResolver;

    #[Required]
    final public function autowireCommentableInterfaceTypeFieldResolver(
        BooleanScalarTypeResolver $booleanScalarTypeResolver,
        IntScalarTypeResolver $intScalarTypeResolver,
        CommentObjectTypeResolver $commentObjectTypeResolver,
    ): void {
        $this->booleanScalarTypeResolver = $booleanScalarTypeResolver;
        $this->intScalarTypeResolver = $intScalarTypeResolver;
        $this->commentObjectTypeResolver = $commentObjectTypeResolver;
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
            'commentCountForAdmin',
            'commentsForAdmin',
        ];
    }

    public function getFieldTypeResolver(string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'comments' => $this->commentObjectTypeResolver,
            'commentsForAdmin' => $this->commentObjectTypeResolver,
            'areCommentsOpen' => $this->booleanScalarTypeResolver,
            'hasComments' => $this->booleanScalarTypeResolver,
            'commentCount' => $this->intScalarTypeResolver,
            'commentCountForAdmin' => $this->intScalarTypeResolver,
            default => parent::getFieldTypeResolver($fieldName),
        };
    }

    public function getFieldTypeModifiers(string $fieldName): int
    {
        return match ($fieldName) {
            'areCommentsOpen',
            'hasComments',
            'commentCount',
            'commentCountForAdmin'
                => SchemaTypeModifiers::NON_NULLABLE,
            'comments',
            'commentsForAdmin'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getFieldTypeModifiers($fieldName),
        };
    }

    public function getFieldDescription(string $fieldName): ?string
    {
        return match ($fieldName) {
            'areCommentsOpen' => $this->translationAPI->__('Are comments open to be added to the custom post', 'pop-comments'),
            'hasComments' => $this->translationAPI->__('Does the custom post have comments?', 'pop-comments'),
            'commentCount' => $this->translationAPI->__('Number of comments added to the custom post', 'pop-comments'),
            'comments' => $this->translationAPI->__('Comments added to the custom post', 'pop-comments'),
            'commentCountForAdmin' => $this->translationAPI->__('[Unrestricted] Number of comments added to the custom post', 'pop-comments'),
            'commentsForAdmin' => $this->translationAPI->__('[Unrestricted] Comments added to the custom post', 'pop-comments'),
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
            'commentsForAdmin' => [
                CommentFilterInputContainerModuleProcessor::class,
                CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_ADMINCOMMENTS
            ],
            'commentCountForAdmin' => [
                CommentFilterInputContainerModuleProcessor::class,
                CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_CUSTOMPOST_ADMINCOMMENTCOUNT
            ],
            default => parent::getFieldFilterInputContainerModule($fieldName),
        };
    }

    public function getFieldArgDefaultValue(string $fieldName, string $fieldArgName): mixed
    {
        switch ($fieldName) {
            case 'comments':
            case 'commentsForAdmin':
            case 'commentCount':
            case 'commentCountForAdmin':
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
            case 'commentsForAdmin':
                $limitFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_LIMIT
                ]);
                if ($fieldArgName === $limitFilterInputName) {
                    return ComponentConfiguration::getCustomPostCommentOrCommentResponseListDefaultLimit();
                }
                $orderFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ORDER
                ]);
                if ($fieldArgName === $orderFilterInputName) {
                    // Order by descending date
                    $orderBy = $this->nameResolver->getName('popcms:dbcolumn:orderby:comments:date');
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
    public function validateFieldArgument(
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        $errors = parent::validateFieldArgument(
            $fieldName,
            $fieldArgName,
            $fieldArgValue,
        );

        // Check the "limit" fieldArg
        switch ($fieldName) {
            case 'comments':
            case 'commentsForAdmin':
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
