<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\ConditionalOnModule\Users\FieldResolvers\ObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoPCMSSchema\MediaMutations\FieldResolvers\ObjectType\AbstractAddCommentToCustomPostObjectTypeFieldResolver as UpstreamAbstractAddCommentToCustomPostObjectTypeFieldResolver;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;

/**
 * This class is placed under ConditionalOnModule/Users/ but there's
 * no need really, as Users will already exist for Mutations packages.
 * It's done like this just to organize the code better.
 */
abstract class AbstractAddCommentToCustomPostObjectTypeFieldResolver extends UpstreamAbstractAddCommentToCustomPostObjectTypeFieldResolver
{
    use AddCommentToCustomPostObjectTypeFieldResolverTrait;

    private ?UserTypeAPIInterface $userTypeAPI = null;

    final public function setUserTypeAPI(UserTypeAPIInterface $userTypeAPI): void
    {
        $this->userTypeAPI = $userTypeAPI;
    }
    final protected function getUserTypeAPI(): UserTypeAPIInterface
    {
        if ($this->userTypeAPI === null) {
            /** @var UserTypeAPIInterface */
            $userTypeAPI = $this->instanceManager->getInstance(UserTypeAPIInterface::class);
            $this->userTypeAPI = $userTypeAPI;
        }
        return $this->userTypeAPI;
    }

    /**
     * Higher priority to override the previous FieldResolver
     */
    public function getPriorityToAttachToClasses(): int
    {
        return parent::getPriorityToAttachToClasses() + 10;
    }

    /**
     * If not provided, set the properties from the logged-in user
     *
     * @param array<string,mixed> $fieldArgs
     * @return array<string,mixed>|null null in case of validation error
     */
    public function prepareFieldArgs(
        array $fieldArgs,
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?array {
        return $this->prepareAddCommentFieldArgs($fieldArgs);
    }
}
