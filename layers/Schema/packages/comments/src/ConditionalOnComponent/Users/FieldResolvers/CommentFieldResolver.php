<?php

declare(strict_types=1);

namespace PoPSchema\Comments\ConditionalOnComponent\Users\FieldResolvers;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\Comments\ConditionalOnComponent\Users\TypeAPIs\CommentTypeAPIInterface as UserCommentTypeAPIInterface;
use PoPSchema\Comments\TypeAPIs\CommentTypeAPIInterface;
use PoPSchema\Comments\FieldResolvers\CommentFieldResolver as UpstreamCommentFieldResolver;
use PoPSchema\Comments\ComponentConfiguration;

/**
 * Override fields from the upstream class, getting the data from the user
 */
class CommentFieldResolver extends UpstreamCommentFieldResolver
{
    function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        NameResolverInterface $nameResolver,
        CMSServiceInterface $cmsService,
        CommentTypeAPIInterface $commentTypeAPI,
        protected UserCommentTypeAPIInterface $userCommentTypeAPI,
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
            $instanceManager,
            $fieldQueryInterpreter,
            $nameResolver,
            $cmsService,
            $commentTypeAPI,
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
    function resolveCanProcessResultItem(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = []
    ): bool {
        $comment = $resultItem;
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
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        $comment = $resultItem;
        $commentUserID = $this->userCommentTypeAPI->getCommentUserId($comment);
        switch ($fieldName) {
            case 'authorName':
                return $cmsusersapi->getUserDisplayName($commentUserID);

            case 'authorURL':
                return $cmsusersapi->getUserURL($commentUserID);

            case 'authorEmail':
                return $cmsusersapi->getUserEmail($commentUserID);
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
