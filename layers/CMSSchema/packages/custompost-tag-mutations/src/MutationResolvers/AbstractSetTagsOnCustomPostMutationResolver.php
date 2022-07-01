<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\MutationResolvers;

use PoP\ComponentModel\Mutation\MutationDataProviderInterface;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\Root\Exception\AbstractException;
use PoPCMSSchema\CustomPostTagMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\CustomPostTagMutations\TypeAPIs\CustomPostTagTypeMutationAPIInterface;
use PoPCMSSchema\UserStateMutations\MutationResolvers\ValidateUserLoggedInMutationResolverTrait;

abstract class AbstractSetTagsOnCustomPostMutationResolver extends AbstractMutationResolver
{
    use ValidateUserLoggedInMutationResolverTrait;

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(MutationDataProviderInterface $mutationDataProvider): mixed
    {
        $customPostID = $mutationDataProvider->get(MutationInputProperties::CUSTOMPOST_ID);
        $postTags = $mutationDataProvider->get(MutationInputProperties::TAGS);
        $append = $mutationDataProvider->get(MutationInputProperties::APPEND);
        $customPostTagTypeAPI = $this->getCustomPostTagTypeMutationAPI();
        $customPostTagTypeAPI->setTags($customPostID, $postTags, $append);
        return $customPostID;
    }

    abstract protected function getCustomPostTagTypeMutationAPI(): CustomPostTagTypeMutationAPIInterface;

    public function validateErrors(MutationDataProviderInterface $mutationDataProvider): array
    {
        // Check that the user is logged-in
        $errorFeedbackItemResolution = $this->validateUserIsLoggedIn();
        if ($errorFeedbackItemResolution !== null) {
            return [
                $errorFeedbackItemResolution,
            ];
        }

        $errors = [];
        if (!$mutationDataProvider->get(MutationInputProperties::CUSTOMPOST_ID)) {
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
