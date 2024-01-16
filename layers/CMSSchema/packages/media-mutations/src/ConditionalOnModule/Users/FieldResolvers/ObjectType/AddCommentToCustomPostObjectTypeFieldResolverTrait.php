<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\ConditionalOnModule\Users\FieldResolvers\ObjectType;

use PoP\Root\App;
use PoPCMSSchema\MediaMutations\Module;
use PoPCMSSchema\MediaMutations\ModuleConfiguration;
use PoPCMSSchema\MediaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;

trait AddCommentToCustomPostObjectTypeFieldResolverTrait
{
    abstract protected function getUserTypeAPI(): UserTypeAPIInterface;

    /**
     * If not provided, set the properties from the logged-in user
     *
     * @param array<string,mixed> $fieldArgs
     * @return array<string,mixed>
     */
    protected function prepareAddCommentFieldArgs(
        array $fieldArgs,
    ): array {
        // Just in case, make sure the InputObject has been set
        $inputValue = $fieldArgs[MutationInputProperties::INPUT] ?? null;
        if ($inputValue === null) {
            return $fieldArgs;
        }
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (
            $moduleConfiguration->mustUserBeLoggedInToAddComment()
            || !App::getState('is-user-logged-in')
        ) {
            return $fieldArgs;
        }
        $userID = App::getState('current-user-id');
        if (!property_exists($inputValue, MutationInputProperties::AUTHOR_NAME)) {
            $inputValue->{MutationInputProperties::AUTHOR_NAME} = $this->getUserTypeAPI()->getUserDisplayName($userID);
        }
        if (!property_exists($inputValue, MutationInputProperties::AUTHOR_EMAIL)) {
            $inputValue->{MutationInputProperties::AUTHOR_EMAIL} = $this->getUserTypeAPI()->getUserEmail($userID);
        }
        if (!property_exists($inputValue, MutationInputProperties::AUTHOR_URL)) {
            $inputValue->{MutationInputProperties::AUTHOR_URL} = $this->getUserTypeAPI()->getUserWebsiteURL($userID);
        }
        $fieldArgs[MutationInputProperties::INPUT] = $inputValue;
        return $fieldArgs;
    }
}
