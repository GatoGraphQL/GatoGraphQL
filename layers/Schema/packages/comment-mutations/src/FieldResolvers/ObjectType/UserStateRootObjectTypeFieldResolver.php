<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoPSchema\CommentMutations\ModuleProcessors\CommentFilterInputContainerModuleProcessor;
use PoPSchema\Comments\ComponentConfiguration;
use PoPSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\FormInputs\OrderFormInput;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use PoPSchema\UserState\FieldResolvers\ObjectType\UserStateObjectTypeFieldResolverTrait;
use Symfony\Contracts\Service\Attribute\Required;

class UserStateRootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use UserStateObjectTypeFieldResolverTrait;
    use WithLimitFieldArgResolverTrait;

    protected CommentTypeAPIInterface $commentTypeAPI;
    protected IntScalarTypeResolver $intScalarTypeResolver;
    protected CommentObjectTypeResolver $commentObjectTypeResolver;

    #[Required]
    public function autowireUserStateRootObjectTypeFieldResolver(
        CommentTypeAPIInterface $commentTypeAPI,
        IntScalarTypeResolver $intScalarTypeResolver,
        CommentObjectTypeResolver $commentObjectTypeResolver,
    ): void {
        $this->commentTypeAPI = $commentTypeAPI;
        $this->intScalarTypeResolver = $intScalarTypeResolver;
        $this->commentObjectTypeResolver = $commentObjectTypeResolver;
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
            'myComment',
            'myCommentCount',
            'myComments',
        ];
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'myCommentCount'
                => $this->intScalarTypeResolver,
            'myComments',
            'myComment'
                => $this->commentObjectTypeResolver,
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getSchemaFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'myCommentCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'myComments'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    protected function getFieldFilterInputDefaultValues(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'myComments':
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
            case 'myComments':
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

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'myComment' => $this->translationAPI->__('Comment by the logged-in user on the site with a specific ID', 'pop-comments'),
            'myCommentCount' => $this->translationAPI->__('Number of comments by the logged-in user on the site', 'pop-comments'),
            'myComments' => $this->translationAPI->__('Comments by the logged-in user on the site', 'pop-comments'),
            default => parent::getSchemaFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldFilterInputContainerModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'myComment' => [CommentFilterInputContainerModuleProcessor::class, CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_MYCOMMENT],
            'myComments' => [CommentFilterInputContainerModuleProcessor::class, CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_MYCOMMENTS],
            'myCommentCount' => [CommentFilterInputContainerModuleProcessor::class, CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_MYCOMMENTCOUNT],
            default => parent::getFieldFilterInputContainerModule($objectTypeResolver, $fieldName),
        };
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
        $vars = ApplicationState::getVars();
        $query = array_merge(
            $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldName, $fieldArgs),
            [
                'authors' => [$vars['global-userstate']['current-user-id']],
            ]
        );
        switch ($fieldName) {
            case 'myCommentCount':
                return $this->commentTypeAPI->getCommentCount($query);
            case 'myComments':
                return $this->commentTypeAPI->getComments($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'myComment':
                if ($comments = $this->commentTypeAPI->getComments($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $comments[0];
                }
                return null;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
