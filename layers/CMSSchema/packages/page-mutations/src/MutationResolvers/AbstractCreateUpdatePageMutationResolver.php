<?php

declare(strict_types=1);

namespace PoPCMSSchema\PageMutations\MutationResolvers;

use PoPCMSSchema\CustomPostMutations\MutationResolvers\AbstractCreateOrUpdateCustomPostMutationResolver;
use PoPCMSSchema\PageMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\PageMutations\LooseContracts\LooseContractSet;
use PoPCMSSchema\Pages\TypeAPIs\PageTypeAPIInterface;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;

abstract class AbstractCreateUpdatePageMutationResolver extends AbstractCreateOrUpdateCustomPostMutationResolver
{
    private ?PageTypeAPIInterface $pageTypeAPI = null;

    final public function setPageTypeAPI(PageTypeAPIInterface $pageTypeAPI): void
    {
        $this->pageTypeAPI = $pageTypeAPI;
    }
    final protected function getPageTypeAPI(): PageTypeAPIInterface
    {
        if ($this->pageTypeAPI === null) {
            /** @var PageTypeAPIInterface */
            $pageTypeAPI = $this->instanceManager->getInstance(PageTypeAPIInterface::class);
            $this->pageTypeAPI = $pageTypeAPI;
        }
        return $this->pageTypeAPI;
    }

    public function getCustomPostType(): string
    {
        return $this->getPageTypeAPI()->getPageCustomPostType();
    }

    protected function validateCanLoggedInUserEditCustomPosts(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

        // Validate user permission
        $userID = App::getState('current-user-id');
        $editPagesCapability = $this->getNameResolver()->getName(LooseContractSet::NAME_EDIT_PAGES_CAPABILITY);
        if (
            !$this->getUserRoleTypeAPI()->userCan(
                $userID,
                $editPagesCapability
            )
        ) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E2,
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
        }

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        parent::validateCanLoggedInUserEditCustomPosts(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function validateCanLoggedInUserPublishCustomPosts(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

        $userID = App::getState('current-user-id');
        $publishPagesCapability = $this->getNameResolver()->getName(LooseContractSet::NAME_PUBLISH_PAGES_CAPABILITY);
        if (
            !$this->getUserRoleTypeAPI()->userCan(
                $userID,
                $publishPagesCapability
            )
        ) {
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E3,
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
        }

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        parent::validateCanLoggedInUserPublishCustomPosts(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }
}
