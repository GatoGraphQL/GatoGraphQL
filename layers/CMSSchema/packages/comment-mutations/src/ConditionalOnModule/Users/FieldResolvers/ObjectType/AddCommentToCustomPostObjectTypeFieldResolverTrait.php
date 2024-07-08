<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentMutations\ConditionalOnModule\Users\FieldResolvers\ObjectType;

use PoP\Root\App;
use PoPCMSSchema\CommentMutations\Module;
use PoPCMSSchema\CommentMutations\ModuleConfiguration;
use PoPCMSSchema\CommentMutations\Constants\MutationInputProperties;
use PoPCMSSchema\SchemaCommons\Constants\MutationInputProperties as SchemaCommonsMutationInputProperties;
use PoPCMSSchema\Users\TypeAPIs\UserTypeAPIInterface;
use stdClass;

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
        /** @var stdClass|null */
        $inputValue = $fieldArgs[MutationInputProperties::INPUT] ?? null;
        if ($inputValue === null) {
            return $fieldArgs;
        }
        /** @var stdClass $inputValue */

        if (!$this->isNotMustUserBeLoggedInToAddCommentAndIsUserLoggedIn()) {
            return $fieldArgs;
        }
        
        $fieldArgs[MutationInputProperties::INPUT] = $this->prepareAddCommentFieldArgsInputValue($inputValue);

        return $fieldArgs;
    }

    /**
     * If not provided, set the properties from the logged-in user
     *
     * @param array<string,mixed> $fieldArgs
     * @return array<string,mixed>
     */
    protected function isNotMustUserBeLoggedInToAddCommentAndIsUserLoggedIn(): bool
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return !$moduleConfiguration->mustUserBeLoggedInToAddComment()
            && App::getState('is-user-logged-in');
    }

    protected function prepareAddCommentFieldArgsInputValue(
        stdClass $inputValue,
    ): stdClass {
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
        return $inputValue;
    }

    /**
     * If not provided, set the properties from the logged-in user
     *
     * @param array<string,mixed> $fieldArgs
     * @return array<string,mixed>
     */
    protected function prepareBulkOperationAddCommentFieldArgs(
        array $fieldArgs,
    ): array {
        // Just in case, make sure the InputObject has been set
        $inputListValue = &$fieldArgs[SchemaCommonsMutationInputProperties::INPUTS] ?? null;
        if ($inputListValue === null) {
            return $fieldArgs;
        }
        /** @var stdClass[] $inputListValue */

        if (!$this->isNotMustUserBeLoggedInToAddCommentAndIsUserLoggedIn()) {
            return $fieldArgs;
        }
        
        foreach ($inputListValue as &$inputValue) {
            $inputValue = $this->prepareAddCommentFieldArgsInputValue($inputValue);
        }        
        
        return $fieldArgs;
    }
}
