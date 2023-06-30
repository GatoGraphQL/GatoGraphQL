<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
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
        if ($this->customPostTypeAPI === null) {
            /** @var CustomPostTypeAPIInterface */
            $customPostTypeAPI = $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
            $this->customPostTypeAPI = $customPostTypeAPI;
        }
        return $this->customPostTypeAPI;
    }

    protected function eligible($post)
    {
        return true;
    }

    public function validate(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        parent::validate($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        if ($objectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return;
        }
        $target_id = $fieldDataAccessor->getValue('target_id');

        // Make sure the post exists
        $target = $this->getCustomPostTypeAPI()->getCustomPost($target_id);
        if (!$target) {
            // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
            $errors = [];
            $errors[] = $this->__('The requested post does not exist.', 'pop-coreprocessors');
        } else {
            // Make sure this target accepts this functionality. Eg: Not all posts can be Recommended or Up/Down-voted.
            // Discussion can be recommended only, Highlight up/down-voted only
            if (!$this->eligible($target)) {
                // @todo Migrate from string to FeedbackItemProvider
            // $objectTypeFieldResolutionFeedbackStore->addError(
            //     new ObjectTypeFieldResolutionFeedback(
            //         new FeedbackItemResolution(
            //             MutationErrorFeedbackItemProvider::class,
            //             MutationErrorFeedbackItemProvider::E1,
            //         ),
            //         $fieldDataAccessor->getField(),
            //     )
            // );
                $errors[] = $this->__('The requested functionality does not apply on this post.', 'pop-coreprocessors');
            }
        }
    }

    protected function getRequestKey()
    {
        return InputNames::POST_ID;
    }

    protected function additionals($target_id, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        App::doAction('gd_updateusermetavalue:post', $target_id, $fieldDataAccessor);
        parent::additionals($target_id, $fieldDataAccessor);
    }
}
