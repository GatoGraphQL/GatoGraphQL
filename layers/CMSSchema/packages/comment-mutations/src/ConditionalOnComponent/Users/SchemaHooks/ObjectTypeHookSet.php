<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\ConditionalOnComponent\Users\SchemaHooks;

use PoP\Root\App;
use PoP\ComponentModel\FieldResolvers\ObjectType\HookNames;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\CommentMutations\Module;
use PoPCMSSchema\CommentMutations\ComponentConfiguration;
use PoPCMSSchema\CommentMutations\FieldResolvers\ObjectType\AbstractAddCommentToCustomPostObjectTypeFieldResolver;
use PoPCMSSchema\CommentMutations\MutationResolvers\MutationInputProperties;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;

class ObjectTypeHookSet extends AbstractHookSet
{
    private ?UserTypeAPIInterface $userTypeAPI = null;

    final public function setUserTypeAPI(UserTypeAPIInterface $userTypeAPI): void
    {
        $this->userTypeAPI = $userTypeAPI;
    }
    final protected function getUserTypeAPI(): UserTypeAPIInterface
    {
        return $this->userTypeAPI ??= $this->instanceManager->getInstance(UserTypeAPIInterface::class);
    }

    protected function init(): void
    {
        App::addFilter(
            HookNames::OBJECT_TYPE_MUTATION_FIELD_ARGS,
            $this->getMutationFieldArgs(...),
            10,
            2
        );
    }

    /**
     * If not provided, set the properties from the logged-in user
     *
     * @param array<string, mixed> $mutationFieldArgs
     */
    public function getMutationFieldArgs(
        array $mutationFieldArgs,
        ObjectTypeFieldResolverInterface $objectTypeFieldResolver,
    ): array {
        if (!($objectTypeFieldResolver instanceof AbstractAddCommentToCustomPostObjectTypeFieldResolver)) {
            return $mutationFieldArgs;
        }

        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Module::class)->getConfiguration();
        if (
            !$componentConfiguration->mustUserBeLoggedInToAddComment()
            && App::getState('is-user-logged-in')
        ) {
            $userID = App::getState('current-user-id');
            if (!isset($mutationFieldArgs[MutationInputProperties::AUTHOR_NAME])) {
                $mutationFieldArgs[MutationInputProperties::AUTHOR_NAME] = $this->getUserTypeAPI()->getUserDisplayName($userID);
            }
            if (!isset($mutationFieldArgs[MutationInputProperties::AUTHOR_EMAIL])) {
                $mutationFieldArgs[MutationInputProperties::AUTHOR_EMAIL] = $this->getUserTypeAPI()->getUserEmail($userID);
            }
            if (!isset($mutationFieldArgs[MutationInputProperties::AUTHOR_URL])) {
                $mutationFieldArgs[MutationInputProperties::AUTHOR_URL] = $this->getUserTypeAPI()->getUserWebsiteURL($userID);
            }
        }
        return $mutationFieldArgs;
    }
}
