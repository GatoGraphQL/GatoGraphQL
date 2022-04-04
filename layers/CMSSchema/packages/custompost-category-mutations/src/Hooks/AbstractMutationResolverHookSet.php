<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoryMutations\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\CustomPostCategoryMutations\MutationResolvers\MutationInputProperties;
use PoPCMSSchema\CustomPostCategoryMutations\TypeAPIs\CustomPostCategoryTypeMutationAPIInterface;
use PoPCMSSchema\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;

abstract class AbstractMutationResolverHookSet extends AbstractHookSet
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

    protected function init(): void
    {
        App::addAction(
            AbstractCreateUpdateCustomPostMutationResolver::HOOK_EXECUTE_CREATE_OR_UPDATE,
            $this->maybeSetCategories(...),
            10,
            2
        );
    }

    public function maybeSetCategories(int | string $customPostID, array $form_data): void
    {
        // Only for that specific CPT
        if ($this->getCustomPostTypeAPI()->getCustomPostType($customPostID) !== $this->getCustomPostType()) {
            return;
        }
        if (!isset($form_data[MutationInputProperties::CATEGORY_IDS])) {
            return;
        }
        $customPostCategoryIDs = $form_data[MutationInputProperties::CATEGORY_IDS];
        $customPostCategoryTypeMutationAPI = $this->getCustomPostCategoryTypeMutationAPI();
        $customPostCategoryTypeMutationAPI->setCategories($customPostID, $customPostCategoryIDs, false);
    }

    abstract protected function getCustomPostType(): string;
    abstract protected function getCustomPostCategoryTypeMutationAPI(): CustomPostCategoryTypeMutationAPIInterface;
}
