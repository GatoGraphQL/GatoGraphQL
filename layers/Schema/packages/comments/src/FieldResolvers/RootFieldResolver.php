<?php

declare(strict_types=1);

namespace PoPSchema\Comments\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\Comments\ComponentConfiguration;
use PoPSchema\Comments\ModuleProcessors\CommentFilterInputContainerModuleProcessor;
use PoPSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPSchema\Comments\TypeResolvers\CommentTypeResolver;
use PoPSchema\CustomPosts\TypeHelpers\CustomPostUnionTypeHelpers;
use PoPSchema\CustomPosts\TypeResolvers\CustomPostUnionTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\FormInputs\OrderFormInput;
use PoPSchema\SchemaCommons\ModuleProcessors\CommonFilterInputContainerModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;

class RootFieldResolver extends AbstractQueryableFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    public function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        NameResolverInterface $nameResolver,
        CMSServiceInterface $cmsService,
        SemverHelperServiceInterface $semverHelperService,
        protected CommentTypeAPIInterface $commentTypeAPI,
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
        return [
            RootTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'comment',
            'commentCount',
            'comments',
            'commentForAdmin',
            'commentCountForAdmin',
            'commentsForAdmin',
        ];
    }

    public function getSchemaFieldType(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): string
    {
        return match ($fieldName) {
            'comment' => SchemaDefinition::TYPE_ID,
            'comments' => SchemaDefinition::TYPE_ID,
            'commentCount' => SchemaDefinition::TYPE_INT,
            'commentForAdmin' => SchemaDefinition::TYPE_ID,
            'commentsForAdmin' => SchemaDefinition::TYPE_ID,
            'commentCountForAdmin' => SchemaDefinition::TYPE_INT,
            default => parent::getSchemaFieldType($relationalTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'commentCount',
            'commentCountForAdmin'
                => SchemaTypeModifiers::NON_NULLABLE,
            'comments',
            'commentsForAdmin'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($relationalTypeResolver, $fieldName),
        };
    }

    protected function getFieldDataFilteringDefaultValues(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'comments':
            case 'commentsForAdmin':
                $limitFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_LIMIT
                ]);
                // Order by descending date
                $orderFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_ORDER
                ]);
                $orderBy = $this->nameResolver->getName('popcms:dbcolumn:orderby:comments:date');
                $order = 'DESC';
                return [
                    $orderFilterInputName => $orderBy . OrderFormInput::SEPARATOR . $order,
                    $limitFilterInputName => ComponentConfiguration::getRootCommentListDefaultLimit(),
                ];
        }
        return parent::getFieldDataFilteringDefaultValues($relationalTypeResolver, $fieldName);
    }

    /**
     * Validate the constraints for a field argument
     *
     * @return string[] Error messages
     */
    public function validateFieldArgument(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        $errors = parent::validateFieldArgument(
            $relationalTypeResolver,
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

    public function getSchemaFieldDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'comment' => $this->translationAPI->__('Comment with a specific ID', 'pop-comments'),
            'commentCount' => $this->translationAPI->__('Number of comments on the site', 'pop-comments'),
            'comments' => $this->translationAPI->__('Comments on the site', 'pop-comments'),
            'commentForAdmin' => $this->translationAPI->__('[Unrestricted] Comment with a specific ID', 'pop-comments'),
            'commentCountForAdmin' => $this->translationAPI->__('[Unrestricted] Number of comments on the site', 'pop-comments'),
            'commentsForAdmin' => $this->translationAPI->__('[Unrestricted] Comments on the site', 'pop-comments'),
            default => parent::getSchemaFieldDescription($relationalTypeResolver, $fieldName),
        };
    }

    public function getFieldDataFilteringModule(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'comment' => [CommonFilterInputContainerModuleProcessor::class, CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_ID],
            'comments' => [CommentFilterInputContainerModuleProcessor::class, CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_COMMENTS],
            'commentCount' => [CommentFilterInputContainerModuleProcessor::class, CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_COMMENTCOUNT],
            'commentForAdmin' => [CommentFilterInputContainerModuleProcessor::class, CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_COMMENT_BY_ID_STATUS],
            'commentsForAdmin' => [CommentFilterInputContainerModuleProcessor::class, CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINCOMMENTS],
            'commentCountForAdmin' => [CommentFilterInputContainerModuleProcessor::class, CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINCOMMENTCOUNT],
            default => parent::getFieldDataFilteringModule($relationalTypeResolver, $fieldName),
        };
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        RelationalTypeResolverInterface $relationalTypeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $query = $this->convertFieldArgsToFilteringQueryArgs($relationalTypeResolver, $fieldName, $fieldArgs);
        switch ($fieldName) {
            case 'commentCount':
            case 'commentCountForAdmin':
                return $this->commentTypeAPI->getCommentCount($query);

            case 'comments':
            case 'commentsForAdmin':
                return $this->commentTypeAPI->getComments($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            case 'comment':
            case 'commentForAdmin':
                /**
                 * Only from the mapped CPTs, otherwise we may get an error when
                 * the custom post to which the comment was added, is not accesible
                 * via field `Comment.customPost`:
                 *
                 *   ```
                 *   comments {
                 *     customPost {
                 *       id
                 *     }
                 *   }
                 *   ```
                 */
                $query['custompost-types'] = CustomPostUnionTypeHelpers::getTargetObjectTypeResolverCustomPostTypes(
                    CustomPostUnionTypeResolver::class
                );
                if ($comments = $this->commentTypeAPI->getComments($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $comments[0];
                }
                return null;
        }

        return parent::resolveValue($relationalTypeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(RelationalTypeResolverInterface $relationalTypeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'comment':
            case 'comments':
            case 'commentForAdmin':
            case 'commentsForAdmin':
                return CommentTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($relationalTypeResolver, $fieldName);
    }
}
