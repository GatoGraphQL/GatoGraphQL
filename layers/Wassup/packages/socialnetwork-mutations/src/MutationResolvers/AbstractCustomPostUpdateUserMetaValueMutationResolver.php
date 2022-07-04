<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\ComponentModel\Mutation\FieldDataProviderInterface;
use PoP\Root\App;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\Posts\Constants\InputNames;

class AbstractCustomPostUpdateUserMetaValueMutationResolver extends AbstractUpdateUserMetaValueMutationResolver
{
    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;

    final public function setCustomPostTypeAPI(CustomPostTypeAPIInterface $customPostTypeAPI): void
    {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }
    final protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        return $this->customPostTypeAPI ??= $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
    }

    protected function eligible($post)
    {
        return true;
    }

    public function validateErrors(FieldDataProviderInterface $fieldDataProvider): array
    {
        $errors = parent::validateErrors($fieldDataProvider);
        if (!$errors) {
            $target_id = $fieldDataProvider->get('target_id');

            // Make sure the post exists
            $target = $this->getCustomPostTypeAPI()->getCustomPost($target_id);
            if (!$target) {
                // @todo Migrate from string to FeedbackItemProvider
                // $errors[] = new FeedbackItemResolution(
                //     MutationErrorFeedbackItemProvider::class,
                //     MutationErrorFeedbackItemProvider::E1,
                // );
                $errors[] = $this->__('The requested post does not exist.', 'pop-coreprocessors');
            } else {
                // Make sure this target accepts this functionality. Eg: Not all posts can be Recommended or Up/Down-voted.
                // Discussion can be recommended only, Highlight up/down-voted only
                if (!$this->eligible($target)) {
                    // @todo Migrate from string to FeedbackItemProvider
                    // $errors[] = new FeedbackItemResolution(
                    //     MutationErrorFeedbackItemProvider::class,
                    //     MutationErrorFeedbackItemProvider::E1,
                    // );
                    $errors[] = $this->__('The requested functionality does not apply on this post.', 'pop-coreprocessors');
                }
            }
        }
        return $errors;
    }

    protected function getRequestKey()
    {
        return InputNames::POST_ID;
    }

    protected function additionals($target_id, FieldDataProviderInterface $fieldDataProvider): void
    {
        App::doAction('gd_updateusermetavalue:post', $target_id, $fieldDataProvider);
        parent::additionals($target_id, $fieldDataProvider);
    }
}
