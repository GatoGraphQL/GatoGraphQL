<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\Hooks;

use PoPCMSSchema\CustomPostMutations\Constants\HookNames;
use PoPCMSSchema\CustomPostTagMutations\Constants\MutationInputProperties;
use PoPCMSSchema\CustomPostTagMutations\TypeAPIs\CustomPostTagTypeMutationAPIInterface;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

abstract class AbstractMutationResolverHookSet extends AbstractHookSet
{
    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;

    final public function setCustomPostTypeAPI(CustomPostTypeAPIInterface $customPostTypeAPI): void
    {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }
    final protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        /** @var CustomPostTypeAPIInterface */
        return $this->customPostTypeAPI ??= $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
    }

    protected function init(): void
    {
        App::addAction(
            HookNames::EXECUTE_CREATE_OR_UPDATE,
            $this->maybeSetTags(...),
            10,
            2
        );
    }

    public function maybeSetTags(int|string $customPostID, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        // Only for that specific CPT
        if ($this->getCustomPostTypeAPI()->getCustomPostType($customPostID) !== $this->getCustomPostType()) {
            return;
        }
        if (!$fieldDataAccessor->hasValue(MutationInputProperties::TAGS)) {
            return;
        }
        $customPostTags = $fieldDataAccessor->getValue(MutationInputProperties::TAGS);
        $customPostTagTypeMutationAPI = $this->getCustomPostTagTypeMutationAPI();
        $customPostTagTypeMutationAPI->setTags($customPostID, $customPostTags, false);
    }

    abstract protected function getCustomPostType(): string;
    abstract protected function getCustomPostTagTypeMutationAPI(): CustomPostTagTypeMutationAPIInterface;
}
