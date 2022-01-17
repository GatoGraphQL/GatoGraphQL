<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMediaMutations\Hooks;

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
            array($this, 'setOrRemoveFeaturedImage'),
            10,
            2
        );
    }

    /**
     * If entry "featuredImageID" has an ID, set it. If it is null, remove it
     */
    public function setOrRemoveFeaturedImage(int | string $customPostID, array $form_data): void
    {
        if (!array_key_exists(MutationInputProperties::FEATUREDIMAGE_ID, $form_data)) {
            return;
        }
        if ($featuredImageID = $form_data[MutationInputProperties::FEATUREDIMAGE_ID]) {
            $this->getCustomPostMediaTypeMutationAPI()->setFeaturedImage($customPostID, $featuredImageID);
        } else {
            $this->getCustomPostMediaTypeMutationAPI()->removeFeaturedImage($customPostID);
        }
    }
}
