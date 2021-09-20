<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\FieldResolvers\ObjectType;

use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\Engine\EngineInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Engine\ComponentConfiguration as EngineComponentConfiguration;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\CommentMutations\MutationResolvers\AddCommentToCustomPostMutationResolver;
use PoPSchema\CommentMutations\Schema\SchemaDefinitionHelpers;
use PoPSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;

class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
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
        protected CommentObjectTypeResolver $commentObjectTypeResolver,
        protected AddCommentToCustomPostMutationResolver $addCommentToCustomPostMutationResolver,
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
            RootObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        if (EngineComponentConfiguration::disableRedundantRootTypeMutationFields()) {
            return [];
        }
        return [
            'addCommentToCustomPost',
            'replyComment',
        ];
    }

    public function getSchemaFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'addCommentToCustomPost' => $this->translationAPI->__('Add a comment to a custom post', 'comment-mutations'),
            'replyComment' => $this->translationAPI->__('Reply a comment with another comment', 'comment-mutations'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($objectTypeResolver, $fieldName);
    }

    public function getSchemaFieldArgs(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): array
    {
        switch ($fieldName) {
            case 'addCommentToCustomPost':
                return SchemaDefinitionHelpers::getAddCommentToCustomPostSchemaFieldArgs($objectTypeResolver, $fieldName, true, true);
            case 'replyComment':
                return SchemaDefinitionHelpers::getAddCommentToCustomPostSchemaFieldArgs($objectTypeResolver, $fieldName, false, true, true);
        }
        return parent::getSchemaFieldArgs($objectTypeResolver, $fieldName);
    }

    public function getFieldMutationResolver(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName
    ): ?MutationResolverInterface {
        switch ($fieldName) {
            case 'addCommentToCustomPost':
            case 'replyComment':
                return $this->addCommentToCustomPostMutationResolver;
        }

        return parent::getFieldMutationResolver($objectTypeResolver, $fieldName);
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        switch ($fieldName) {
            case 'addCommentToCustomPost':
            case 'replyComment':
                return $this->commentObjectTypeResolver;
        }

        return parent::getFieldTypeResolver($objectTypeResolver, $fieldName);
    }
}
