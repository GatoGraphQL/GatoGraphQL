<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostCategoryMutations\Hooks;

use PoPCMSSchema\Categories\TypeAPIs\CategoryTypeAPIInterface;
use PoPCMSSchema\CustomPostCategoryMutations\Hooks\AbstractMutationResolverHookSet;
use PoPCMSSchema\PostCategories\TypeAPIs\PostCategoryTypeAPIInterface;
use PoPCMSSchema\PostMutations\Constants\PostCRUDHookNames;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;

class MutationResolverHookSet extends AbstractMutationResolverHookSet
{
    private ?PostCategoryTypeAPIInterface $postCategoryTypeAPI = null;

    final public function setPostCategoryTypeAPI(PostCategoryTypeAPIInterface $postCategoryTypeAPI): void
    {
        $this->postCategoryTypeAPI = $postCategoryTypeAPI;
    }
    final protected function getPostCategoryTypeAPI(): PostCategoryTypeAPIInterface
    {
        if ($this->postCategoryTypeAPI === null) {
            /** @var PostCategoryTypeAPIInterface */
            $postCategoryTypeAPI = $this->instanceManager->getInstance(PostCategoryTypeAPIInterface::class);
            $this->postCategoryTypeAPI = $postCategoryTypeAPI;
        }
        return $this->postCategoryTypeAPI;
    }

    protected function getCategoryTypeAPI(): CategoryTypeAPIInterface
    {
        return $this->getPostCategoryTypeAPI();
    }

    protected function getCategoryTaxonomyName(
        int|string $customPostID,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): ?string {
        return $this->getPostCategoryTypeAPI()->getPostCategoryTaxonomyName();
    }

    protected function getValidateCreateOrUpdateHookName(): string
    {
        return PostCRUDHookNames::VALIDATE_CREATE_OR_UPDATE;
    }
    protected function getExecuteCreateOrUpdateHookName(): string
    {
        return PostCRUDHookNames::EXECUTE_CREATE_OR_UPDATE;
    }
}
