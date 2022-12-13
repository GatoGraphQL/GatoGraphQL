<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;

abstract class AbstractSubscribeToOrUnsubscribeFromTagMutationResolver extends AbstractUpdateUserMetaValueMutationResolver
{
    private ?PostTagTypeAPIInterface $postTagTypeAPI = null;

    final public function setPostTagTypeAPI(PostTagTypeAPIInterface $postTagTypeAPI): void
    {
        $this->postTagTypeAPI = $postTagTypeAPI;
    }
    final protected function getPostTagTypeAPI(): PostTagTypeAPIInterface
    {
        /** @var PostTagTypeAPIInterface */
        return $this->postTagTypeAPI ??= $this->instanceManager->getInstance(PostTagTypeAPIInterface::class);
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
        $target = $this->getPostTagTypeAPI()->getTag($target_id);
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
            $errors[] = $this->__('The requested topic/tag does not exist.', 'pop-coreprocessors');
        }
    }

    protected function additionals($target_id, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        App::doAction('gd_subscritetounsubscribefrom_tag', $target_id, $fieldDataAccessor);
        parent::additionals($target_id, $fieldDataAccessor);
    }
}
