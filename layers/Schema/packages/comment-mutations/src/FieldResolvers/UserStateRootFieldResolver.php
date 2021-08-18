<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractQueryableFieldResolver;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\State\ApplicationState;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Engine\TypeResolvers\RootTypeResolver;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\CommentMutations\ModuleProcessors\CommentFilterInputContainerModuleProcessor;
use PoPSchema\Comments\ComponentConfiguration;
use PoPSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPSchema\Comments\TypeResolvers\CommentTypeResolver;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\FormInputs\OrderFormInput;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\UserState\FieldResolvers\UserStateFieldResolverTrait;

class UserStateRootFieldResolver extends AbstractQueryableFieldResolver
{
    use UserStateFieldResolverTrait;

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
            'myCommentCount',
            'myComments',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        return match ($fieldName) {
            'myComments' => SchemaDefinition::TYPE_ID,
            'myCommentCount' => SchemaDefinition::TYPE_INT,
            default => parent::getSchemaFieldType($typeResolver, $fieldName),
        };
    }

    public function getSchemaFieldTypeModifiers(TypeResolverInterface $typeResolver, string $fieldName): ?int
    {
        return match ($fieldName) {
            'myCommentCount'
                => SchemaTypeModifiers::NON_NULLABLE,
            'myComments'
                => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getSchemaFieldTypeModifiers($typeResolver, $fieldName),
        };
    }

    protected function getFieldDataFilteringDefaultValues(TypeResolverInterface $typeResolver, string $fieldName): array
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
        return parent::getFieldDataFilteringDefaultValues($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'myCommentCount' => $this->translationAPI->__('Number of comments by the logged-in user on the site', 'pop-comments'),
            'myComments' => $this->translationAPI->__('Comments by the logged-in user on the site', 'pop-comments'),
            default => parent::getSchemaFieldDescription($typeResolver, $fieldName),
        };
    }

    public function getFieldDataFilteringModule(TypeResolverInterface $typeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'myComments' => [CommentFilterInputContainerModuleProcessor::class, CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_MYCOMMENTS],
            'myCommentCount' => [CommentFilterInputContainerModuleProcessor::class, CommentFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_MYCOMMENTCOUNT],
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
        $vars = ApplicationState::getVars();
        $query = [
            'authors' => [$vars['global-userstate']['current-user-id']],
        ];
        switch ($fieldName) {
            case 'myCommentCount':
                $options = $this->getFilterDataloadQueryArgsOptions($typeResolver, $fieldName, $fieldArgs);
                return $this->commentTypeAPI->getCommentCount($query, $options);

            case 'myComments':
                $options = array_merge(
                    [
                        'return-type' => ReturnTypes::IDS,
                    ],
                    $this->getFilterDataloadQueryArgsOptions($typeResolver, $fieldName, $fieldArgs)
                );
                return $this->commentTypeAPI->getComments($query, $options);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    public function resolveFieldTypeResolverClass(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        switch ($fieldName) {
            case 'myComments':
                return CommentTypeResolver::class;
        }

        return parent::resolveFieldTypeResolverClass($typeResolver, $fieldName);
    }
}
