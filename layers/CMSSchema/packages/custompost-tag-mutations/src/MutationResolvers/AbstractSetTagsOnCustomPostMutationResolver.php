<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\MutationResolvers;

use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\Root\Exception\AbstractException;
use PoPCMSSchema\CustomPostTagMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\CustomPostTagMutations\TypeAPIs\CustomPostTagTypeMutationAPIInterface;
use PoPCMSSchema\UserStateMutations\MutationResolvers\ValidateUserLoggedInMutationResolverTrait;

abstract class AbstractSetTagsOnCustomPostMutationResolver extends AbstractMutationResolver
{
    use ValidateUserLoggedInMutationResolverTrait;

    /**
     * @param array<string,mixed> $form_data
     * @throws AbstractException In case of error
     */
    public function executeMutation(array $form_data): mixed
    {
        $customPostID = $form_data[MutationInputProperties::CUSTOMPOST_ID];
        $postTags = $form_data[MutationInputProperties::TAGS];
        $append = $form_data[MutationInputProperties::APPEND];
        $customPostTagTypeAPI = $this->getCustomPostTagTypeMutationAPI();
        $customPostTagTypeAPI->setTags($customPostID, $postTags, $append);
        return $customPostID;
    }

    abstract protected function getCustomPostTagTypeMutationAPI(): CustomPostTagTypeMutationAPIInterface;

    public function validateErrors(array $form_data): array
    {
        // Check that the user is logged-in
        $errorFeedbackItemResolution = $this->validateUserIsLoggedIn();
        if ($errorFeedbackItemResolution !== null) {
            return [
                $errorFeedbackItemResolution,
            ];
        }
        
        $errors = [];
        if (!($form_data[MutationInputProperties::CUSTOMPOST_ID] ?? null)) {
            $errors[] = new FeedbackItemResolution(
                MutationErrorFeedbackItemProvider::class,
                MutationErrorFeedbackItemProvider::E1,
                [
                    $this->getEntityName(),
                ]
            );
        }
        return $errors;
    }

    protected function getEntityName(): string
    {
        return $this->__('custom post', 'custompost-tag-mutations');
    }
}
