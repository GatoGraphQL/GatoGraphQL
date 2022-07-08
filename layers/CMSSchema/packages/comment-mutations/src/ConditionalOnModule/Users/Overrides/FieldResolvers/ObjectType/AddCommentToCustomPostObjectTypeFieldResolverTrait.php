<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\ConditionalOnModule\Users\Overrides\FieldResolvers\ObjectType;

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
     *
     * @param array<string,mixed> $fieldData
     * @return array<string,mixed>
     */
    protected function prepareAddCommentFieldData(
        array $fieldData,
    ): array {
        // Just in case, make sure the InputObject has been set
        $inputValue = $fieldData[MutationInputProperties::INPUT] ?? null;
        if ($inputValue === null) {
            return $fieldData;
        }
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (
            $moduleConfiguration->mustUserBeLoggedInToAddComment()
            || !App::getState('is-user-logged-in')
        ) {
            return $fieldData;
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
        $fieldData[MutationInputProperties::INPUT] = $inputValue;
        return $fieldData;
    }
}
