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
use stdClass;

abstract class AbstractMutationResolverHookSet extends AbstractHookSet
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
        if (!$fieldDataAccessor->hasValue(MutationInputProperties::TAGS_BY)) {
            return;
        }
        /** @var stdClass */
        $tagsBy = $fieldDataAccessor->getValue(MutationInputProperties::TAGS_BY);
        if (((array) $tagsBy) === []) {
            return;
        }
        $customPostTags = isset($tagsBy->{MutationInputProperties::IDS})
            ? $tagsBy->{MutationInputProperties::IDS}
            : $tagsBy->{MutationInputProperties::SLUGS};
        $this->getCustomPostTagTypeMutationAPI()->setTags($customPostID, $customPostTags, false);
    }

    abstract protected function getCustomPostType(): string;
    abstract protected function getCustomPostTagTypeMutationAPI(): CustomPostTagTypeMutationAPIInterface;
}
