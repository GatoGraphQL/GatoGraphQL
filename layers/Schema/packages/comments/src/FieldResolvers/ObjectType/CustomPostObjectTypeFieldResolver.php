<?php

declare(strict_types=1);

namespace PoPSchema\Comments\FieldResolvers\ObjectType;

use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Engine\EngineInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\Comments\FieldResolvers\InterfaceType\CommentableInterfaceTypeFieldResolver;
use PoPSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPSchema\CustomPosts\TypeResolvers\ObjectType\AbstractCustomPostObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;

class CustomPostObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
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
        protected CommentTypeAPIInterface $commentTypeAPI,
        protected CommentableInterfaceTypeFieldResolver $commentableInterfaceTypeFieldResolver,
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
            AbstractCustomPostObjectTypeResolver::class,
        ];
    }

    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->commentableInterfaceTypeFieldResolver,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'areCommentsOpen',
            'hasComments',
            'commentCount',
            'commentCountForAdmin',
            'comments',
            'commentsForAdmin',
        ];
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
        $post = $object;
        switch ($fieldName) {
            case 'areCommentsOpen':
                return $this->commentTypeAPI->areCommentsOpen($objectTypeResolver->getID($post));

            case 'hasComments':
                return $objectTypeResolver->resolveValue($post, 'commentCount', $variables, $expressions, $options) > 0;
        }

        $query = array_merge(
            $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldName, $fieldArgs),
            [
                'customPostID' => $objectTypeResolver->getID($post),
            ]
        );
        switch ($fieldName) {
            case 'commentCount':
            case 'commentCountForAdmin':
                return $this->commentTypeAPI->getCommentCount($query);

            case 'comments':
            case 'commentsForAdmin':
                return $this->commentTypeAPI->getComments($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
