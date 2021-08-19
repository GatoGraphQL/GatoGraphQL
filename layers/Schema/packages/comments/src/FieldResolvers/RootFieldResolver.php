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
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
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
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\FormInputs\OrderFormInput;
use PoPSchema\SchemaCommons\ModuleProcessors\CommonFilterInputContainerModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;

class RootFieldResolver extends AbstractQueryableFieldResolver
{
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
            'unrestrictedComment',
            'unrestrictedCommentCount',
            'unrestrictedComments',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        return match ($fieldName) {
            'comment' => SchemaDefinition::TYPE_ID,
            'comments' => SchemaDefinition::TYPE_ID,
            'commentCount' => SchemaDefinition::TYPE_INT,
            'unrestrictedComment' => SchemaDefinition::TYPE_ID,
            'unrestrictedComments' => SchemaDefinition::TYPE_ID,
            'unrestrictedCommentCount' => SchemaDefinition::TYPE_INT,
            default => parent::getSchemaFieldType($typeResolver, $fieldName),
        };
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'commentCount',
            'unrestrictedCommentCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'comments',
            'unrestrictedComments'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName),
        };
    }

    protected function getFieldDataFilteringDefaultValues(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'comments':
            case 'unrestrictedComments':
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
        return parent::getFieldDataFilteringDefaultValues($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'comment' => $this->translationAPI->__('Comment with a specific ID', 'pop-comments'),
            'commentCount' => $this->translationAPI->__('Number of comments on the site', 'pop-comments'),
            'comments' => $this->translationAPI->__('Comments on the site', 'pop-comments'),
            'unrestrictedComment' => $this->translationAPI->__('[Unrestricted] Comment with a specific ID', 'pop-comments'),
            'unrestrictedCommentCount' => $this->translationAPI->__('[Unrestricted] Number of comments on the site', 'pop-comments'),
            'unrestrictedComments' => $this->translationAPI->__('[Unrestricted] Comments on the site', 'pop-comments'),
            default => parent::getSchemaFieldDescription($typeResolver, $fieldName),
        };
    }

    public function getFieldDataFilteringModule(TypeResolverInterface $typeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'comment' => [CommonFilterInputContainerModuleProcessor::class, CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_ID],
            'comments' => [CommentFilterInputContainerModuleProcessor::class, CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_COMMENTS],
            'commentCount' => [CommentFilterInputContainerModuleProcessor::class, CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_COMMENTCOUNT],
            'unrestrictedComment' => [CommentFilterInputContainerModuleProcessor::class, CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_COMMENT_BY_ID_STATUS],
            'unrestrictedComments' => [CommentFilterInputContainerModuleProcessor::class, CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINCOMMENTS],
            'unrestrictedCommentCount' => [CommentFilterInputContainerModuleProcessor::class, CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ADMINCOMMENTCOUNT],
            default => parent::getFieldDataFilteringModule($typeResolver, $fieldName),
        };
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
        switch ($fieldName) {
            case 'commentCount':
            case 'unrestrictedCommentCount':
                $options = $this->getFilterDataloadQueryArgsOptions($typeResolver, $fieldName, $fieldArgs);
                return $this->commentTypeAPI->getCommentCount([], $options);
            case 'comments':
            case 'unrestrictedComments':
                $options = array_merge(
                    [
                        'return-type' => ReturnTypes::IDS,
                    ],
                    $this->getFilterDataloadQueryArgsOptions($typeResolver, $fieldName, $fieldArgs)
                );
                return $this->commentTypeAPI->getComments([], $options);
            case 'comment':
            case 'unrestrictedComment':
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
                $query = [
                    'custompost-types' => CustomPostUnionTypeHelpers::getTargetTypeResolverCustomPostTypes(
                        CustomPostUnionTypeResolver::class
                    ),
                ];
                $options = array_merge(
                    [
                        'return-type' => ReturnTypes::IDS,
                    ],
                    $this->getFilterDataloadQueryArgsOptions($typeResolver, $fieldName, $fieldArgs)
                );
                if ($comments = $this->commentTypeAPI->getComments($query, $options)) {
                    return $comments[0];
                }
                return null;
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'comment':
            case 'comments':
            case 'unrestrictedComment':
            case 'unrestrictedComments':
                return CommentTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
