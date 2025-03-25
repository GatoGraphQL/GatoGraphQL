<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMetaMutations\MutationResolvers;

use PoPCMSSchema\CustomPostCategoryMetaMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostCategoryMetaMutations\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoPCMSSchema\CustomPostCategoryMetaMutations\TypeAPIs\CustomPostCategoryMetaTypeMutationAPIInterface;
use PoPCMSSchema\CategoryMutations\MutationResolvers\CreateOrUpdateCustomPostMutationResolverTrait;
use PoPCMSSchema\CategoryMutations\TypeAPIs\CustomPostTypeMutationAPIInterface;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTermTypeAPIInterface;
use PoPCMSSchema\TaxonomyMetaMutations\MutationResolvers\MutateTaxonomyTermMetaMutationResolverTrait;
use PoPCMSSchema\UserRoles\TypeAPIs\UserRoleTypeAPIInterface;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Root\Exception\AbstractException;

abstract class AbstractSetMetaOnCategoryMutationResolver extends AbstractMutationResolver
{
    use CreateOrUpdateCustomPostMutationResolverTrait, MutateTaxonomyTermMetaMutationResolverTrait, SetMetaOnCategoryMutationResolverTrait {
        CreateOrUpdateCustomPostMutationResolverTrait::getUserNotLoggedInError insteadof MutateTaxonomyTermMetaMutationResolverTrait, SetMetaOnCategoryMutationResolverTrait;
        CreateOrUpdateCustomPostMutationResolverTrait::validateIsUserLoggedIn insteadof MutateTaxonomyTermMetaMutationResolverTrait, SetMetaOnCategoryMutationResolverTrait;
        SetMetaOnCategoryMutationResolverTrait::getTaxonomyTermDoesNotExistError insteadof MutateTaxonomyTermMetaMutationResolverTrait;
        SetMetaOnCategoryMutationResolverTrait::getTaxonomyTermBySlugDoesNotExistError insteadof MutateTaxonomyTermMetaMutationResolverTrait;
    }

    private ?NameResolverInterface $nameResolver = null;
    private ?UserRoleTypeAPIInterface $userRoleTypeAPI = null;
    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;
    private ?CustomPostTypeMutationAPIInterface $customPostTypeMutationAPI = null;
    private ?CustomPostCategoryMetaTypeMutationAPIInterface $customPostCategoryTypeMutationAPI = null;
    private ?TaxonomyTermTypeAPIInterface $taxonomyTermTypeAPI = null;

    final protected function getNameResolver(): NameResolverInterface
    {
        if ($this->nameResolver === null) {
            /** @var NameResolverInterface */
            $nameResolver = $this->instanceManager->getInstance(NameResolverInterface::class);
            $this->nameResolver = $nameResolver;
        }
        return $this->nameResolver;
    }
    final protected function getUserRoleTypeAPI(): UserRoleTypeAPIInterface
    {
        if ($this->userRoleTypeAPI === null) {
            /** @var UserRoleTypeAPIInterface */
            $userRoleTypeAPI = $this->instanceManager->getInstance(UserRoleTypeAPIInterface::class);
            $this->userRoleTypeAPI = $userRoleTypeAPI;
        }
        return $this->userRoleTypeAPI;
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
    final protected function getCustomPostTypeMutationAPI(): CustomPostTypeMutationAPIInterface
    {
        if ($this->customPostTypeMutationAPI === null) {
            /** @var CustomPostTypeMutationAPIInterface */
            $customPostTypeMutationAPI = $this->instanceManager->getInstance(CustomPostTypeMutationAPIInterface::class);
            $this->customPostTypeMutationAPI = $customPostTypeMutationAPI;
        }
        return $this->customPostTypeMutationAPI;
    }
    final protected function getCustomPostCategoryMetaTypeMutationAPI(): CustomPostCategoryMetaTypeMutationAPIInterface
    {
        if ($this->customPostCategoryTypeMutationAPI === null) {
            /** @var CustomPostCategoryMetaTypeMutationAPIInterface */
            $customPostCategoryTypeMutationAPI = $this->instanceManager->getInstance(CustomPostCategoryMetaTypeMutationAPIInterface::class);
            $this->customPostCategoryTypeMutationAPI = $customPostCategoryTypeMutationAPI;
        }
        return $this->customPostCategoryTypeMutationAPI;
    }
    final protected function getTaxonomyTermTypeAPI(): TaxonomyTermTypeAPIInterface
    {
        if ($this->taxonomyTermTypeAPI === null) {
            /** @var TaxonomyTermTypeAPIInterface */
            $taxonomyTermTypeAPI = $this->instanceManager->getInstance(TaxonomyTermTypeAPIInterface::class);
            $this->taxonomyTermTypeAPI = $taxonomyTermTypeAPI;
        }
        return $this->taxonomyTermTypeAPI;
    }

    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $customPostID = $fieldDataAccessor->getValue(MutationInputProperties::CATEGORY_ID);
        $append = $fieldDataAccessor->getValue(MutationInputProperties::APPEND);

        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

        $this->setMetaOnCategory(
            $customPostID,
            $append,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return null;
        }

        return $customPostID;
    }

    public function validate(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();

        $this->validateIsUserLoggedIn(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        $customPostID = $fieldDataAccessor->getValue(MutationInputProperties::CATEGORY_ID);
        $this->validateCustomPostExists(
            $customPostID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $this->validateCanLoggedInUserEditCustomPosts(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $this->validateCanLoggedInUserEditCustomPost(
            $customPostID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        $this->validateCustomPostTypeIsNotEmpty(
            $customPostID,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );

        if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
            return;
        }

        /** @var string */
        $customPostType = $this->getCustomPostTypeAPI()->getCustomPostType($customPostID);
        $this->validateSetMetaOnCategory(
            $customPostType,
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    protected function getUserNotLoggedInError(): FeedbackItemResolution
    {
        return new FeedbackItemResolution(
            MutationErrorFeedbackItemProvider::class,
            MutationErrorFeedbackItemProvider::E1,
        );
    }

    protected function getEntityName(): string
    {
        return $this->__('custom post', 'custompost-categorymeta-mutations');
    }
}
