<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\ConditionalOnComponent\Users\FieldResolvers\ObjectType;

use PoP\ComponentModel\Engine\EngineInterface;
use PoP\ComponentModel\HelperServices\SemverHelperServiceInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\ModuleProcessors\ModuleProcessorManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinitionServiceInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Engine\Formatters\DateFormatterInterface;
use PoP\Engine\TypeResolvers\ScalarType\BooleanScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\CommentMutations\ComponentConfiguration;
use PoPSchema\Comments\ConditionalOnComponent\Users\TypeAPIs\CommentTypeAPIInterface as UserCommentTypeAPIInterface;
use PoPSchema\Comments\FieldResolvers\ObjectType\CommentObjectTypeFieldResolver as UpstreamCommentObjectTypeFieldResolver;
use PoPSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPSchema\Comments\TypeResolvers\EnumType\CommentStatusEnumTypeResolver;
use PoPSchema\Comments\TypeResolvers\ObjectType\CommentObjectTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EmailScalarTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\URLScalarTypeResolver;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;

/**
 * Override fields from the upstream class, getting the data from the user
 */
class CommentObjectTypeFieldResolver extends UpstreamCommentObjectTypeFieldResolver
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
        CommentTypeAPIInterface $commentTypeAPI,
        StringScalarTypeResolver $stringScalarTypeResolver,
        URLScalarTypeResolver $urlScalarTypeResolver,
        EmailScalarTypeResolver $emailScalarTypeResolver,
        IDScalarTypeResolver $idScalarTypeResolver,
        BooleanScalarTypeResolver $booleanScalarTypeResolver,
        DateScalarTypeResolver $dateScalarTypeResolver,
        IntScalarTypeResolver $intScalarTypeResolver,
        CommentObjectTypeResolver $commentObjectTypeResolver,
        CommentStatusEnumTypeResolver $commentStatusEnumTypeResolver,
        DateFormatterInterface $dateFormatter,
        protected UserCommentTypeAPIInterface $userCommentTypeAPI,
        protected UserTypeAPIInterface $userTypeAPI,
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
            $commentTypeAPI,
            $stringScalarTypeResolver,
            $urlScalarTypeResolver,
            $emailScalarTypeResolver,
            $idScalarTypeResolver,
            $booleanScalarTypeResolver,
            $dateScalarTypeResolver,
            $intScalarTypeResolver,
            $commentObjectTypeResolver,
            $commentStatusEnumTypeResolver,
            $dateFormatter,
        );
    }

    /**
     * Execute before the upstream class
     */
    public function getPriorityToAttachToClasses(): int
    {
        return 20;
    }

    /**
     * Only use it when `mustUserBeLoggedInToAddComment`.
     * Check on runtime (not via container) since this option can be changed in WP.
     */
    public function isServiceEnabled(): bool
    {
        return ComponentConfiguration::mustUserBeLoggedInToAddComment();
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'authorName',
            'authorURL',
            'authorEmail',
        ];
    }

    /**
     * Check there is an author. Otherwise, let the upstream resolve it
     */
    public function resolveCanProcessObject(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = []
    ): bool {
        $comment = $object;
        $commentUserID = $this->userCommentTypeAPI->getCommentUserId($comment);
        return $commentUserID !== null;
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
        $comment = $object;
        $commentUserID = $this->userCommentTypeAPI->getCommentUserId($comment);
        switch ($fieldName) {
            case 'authorName':
                return $this->userTypeAPI->getUserDisplayName($commentUserID);

            case 'authorURL':
                return $this->userTypeAPI->getUserWebsiteURL($commentUserID);

            case 'authorEmail':
                return $this->userTypeAPI->getUserEmail($commentUserID);
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
