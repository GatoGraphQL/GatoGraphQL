<?php

declare(strict_types=1);

namespace PoPSchema\CommentMutations\ConditionalOnComponent\Users\SchemaHooks;

use PoP\Engine\App;
use PoP\Root\Managers\ComponentManager;
use PoP\ComponentModel\FieldResolvers\ObjectType\HookNames;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\State\ApplicationState;
use PoP\BasicService\AbstractHookSet;
use PoPSchema\CommentMutations\Component;
use PoPSchema\CommentMutations\ComponentConfiguration;
use PoPSchema\CommentMutations\FieldResolvers\ObjectType\AbstractAddCommentToCustomPostObjectTypeFieldResolver;
use PoPSchema\CommentMutations\MutationResolvers\MutationInputProperties;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;

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
        $this->getHooksAPI()->addFilter(
            HookNames::OBJECT_TYPE_MUTATION_FIELD_ARGS,
            [$this, 'getMutationFieldArgs'],
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

        $vars = ApplicationState::getVars();
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponentManager()->getComponent(Component::class)->getConfiguration();
        if (
            !$componentConfiguration->mustUserBeLoggedInToAddComment()
            && $vars['global-userstate']['is-user-logged-in']
        ) {
            $userID = $vars['global-userstate']['current-user-id'];
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
