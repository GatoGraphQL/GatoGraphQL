<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\Hooks;

use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\CustomPostMediaMutations\MutationResolvers\MutationInputProperties;
use PoPCMSSchema\CustomPostMediaMutations\TypeAPIs\CustomPostMediaTypeMutationAPIInterface;
use PoPCMSSchema\CustomPostMutations\MutationResolvers\AbstractCreateUpdateCustomPostMutationResolver;

class MutationResolverHookSet extends AbstractHookSet
{
    private ?CustomPostMediaTypeMutationAPIInterface $customPostMediaTypeMutationAPI = null;

    final public function setCustomPostMediaTypeMutationAPI(CustomPostMediaTypeMutationAPIInterface $customPostMediaTypeMutationAPI): void
    {
        $this->customPostMediaTypeMutationAPI = $customPostMediaTypeMutationAPI;
    }
    final protected function getCustomPostMediaTypeMutationAPI(): CustomPostMediaTypeMutationAPIInterface
    {
        return $this->customPostMediaTypeMutationAPI ??= $this->instanceManager->getInstance(CustomPostMediaTypeMutationAPIInterface::class);
    }

    protected function init(): void
    {
        App::addAction(
            AbstractCreateUpdateCustomPostMutationResolver::HOOK_EXECUTE_CREATE_OR_UPDATE,
            $this->setOrRemoveFeaturedImage(...),
            10,
            2
        );
    }

    /**
     * If entry "featuredImageID" has an ID, set it. If it is null, remove it
     */
    public function setOrRemoveFeaturedImage(int | string $customPostID, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        if (!$fieldDataAccessor->hasValue(MutationInputProperties::FEATUREDIMAGE_ID)) {
            return;
        }
        if ($featuredImageID = $fieldDataAccessor->getValue(MutationInputProperties::FEATUREDIMAGE_ID)) {
            $this->getCustomPostMediaTypeMutationAPI()->setFeaturedImage($customPostID, $featuredImageID);
        } else {
            $this->getCustomPostMediaTypeMutationAPI()->removeFeaturedImage($customPostID);
        }
    }
}
