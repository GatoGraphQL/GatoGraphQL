<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\ConditionalOnModule\Users\FieldResolvers\ObjectType;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\App;
use PoPCMSSchema\CommentMutations\Module;
use PoPCMSSchema\CommentMutations\ModuleConfiguration;
use PoPCMSSchema\CommentMutations\MutationResolvers\MutationInputProperties;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;

trait AddCommentToCustomPostObjectTypeFieldResolverTrait
{
    abstract protected function getUserTypeAPI(): UserTypeAPIInterface;

    /**
     * If not provided, set the properties from the logged-in user
     */
    protected function customizeAddCommentField(
        FieldInterface $field,
    ): void {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (
            !$moduleConfiguration->mustUserBeLoggedInToAddComment()
            && App::getState('is-user-logged-in')
        ) {
            $userID = App::getState('current-user-id');
            if (!$field->hasArgument(MutationInputProperties::AUTHOR_NAME)) {
                $field->addArgument(
                    new Argument(
                        MutationInputProperties::AUTHOR_NAME,
                        new Literal(
                            $this->getUserTypeAPI()->getUserDisplayName($userID),
                            LocationHelper::getNonSpecificLocation()
                        ),
                        LocationHelper::getNonSpecificLocation()
                    )
                );
            }
            if (!$field->hasArgument(MutationInputProperties::AUTHOR_EMAIL)) {
                $field->addArgument(
                    new Argument(
                        MutationInputProperties::AUTHOR_EMAIL,
                        new Literal(
                            $this->getUserTypeAPI()->getUserEmail($userID),
                            LocationHelper::getNonSpecificLocation()
                        ),
                        LocationHelper::getNonSpecificLocation()
                    )
                );
            }
            if (!$field->hasArgument(MutationInputProperties::AUTHOR_URL)) {
                $field->addArgument(
                    new Argument(
                        MutationInputProperties::AUTHOR_URL,
                        new Literal(
                            $this->getUserTypeAPI()->getUserWebsiteURL($userID),
                            LocationHelper::getNonSpecificLocation()
                        ),
                        LocationHelper::getNonSpecificLocation()
                    )
                );
            }
        }
    }
}
