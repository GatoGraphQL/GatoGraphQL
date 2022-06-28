<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\ConditionalOnModule\Users\SchemaHooks;

use PoP\ComponentModel\FieldResolvers\ObjectType\HookNames;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\CommentMutations\FieldResolvers\ObjectType\AbstractAddCommentToCustomPostObjectTypeFieldResolver;
use PoPCMSSchema\CommentMutations\Module;
use PoPCMSSchema\CommentMutations\ModuleConfiguration;
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
     * @param Argument[] $mutationFieldArgs
     */
    public function getMutationFieldArgs(
        array $mutationFieldArgs,
        ObjectTypeFieldResolverInterface $objectTypeFieldResolver,
    ): array {
        if (!($objectTypeFieldResolver instanceof AbstractAddCommentToCustomPostObjectTypeFieldResolver)) {
            return $mutationFieldArgs;
        }

        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (
            !$moduleConfiguration->mustUserBeLoggedInToAddComment()
            && App::getState('is-user-logged-in')
        ) {
            $userID = App::getState('current-user-id');
            if (array_filter($mutationFieldArgs, fn (Argument $argument) => $argument->getName() === MutationInputProperties::AUTHOR_NAME) === []) {
                $mutationFieldArgs[] = new Argument(
                    MutationInputProperties::AUTHOR_NAME,
                    new Literal(
                        $this->getUserTypeAPI()->getUserDisplayName($userID),
                        LocationHelper::getNonSpecificLocation()
                    ),
                    LocationHelper::getNonSpecificLocation()
                );
            }
            if (array_filter($mutationFieldArgs, fn (Argument $argument) => $argument->getName() === MutationInputProperties::AUTHOR_EMAIL) === []) {
                $mutationFieldArgs[] = new Argument(
                    MutationInputProperties::AUTHOR_EMAIL,
                    new Literal(
                        $this->getUserTypeAPI()->getUserEmail($userID),
                        LocationHelper::getNonSpecificLocation()
                    ),
                    LocationHelper::getNonSpecificLocation()
                );
            }
            if (array_filter($mutationFieldArgs, fn (Argument $argument) => $argument->getName() === MutationInputProperties::AUTHOR_URL) === []) {
                $mutationFieldArgs[] = new Argument(
                    MutationInputProperties::AUTHOR_URL,
                    new Literal(
                        $this->getUserTypeAPI()->getUserWebsiteURL($userID),
                        LocationHelper::getNonSpecificLocation()
                    ),
                    LocationHelper::getNonSpecificLocation()
                );
            }
        }
        return $mutationFieldArgs;
    }
}
