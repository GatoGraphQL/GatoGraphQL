<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\State;

use PoP\Root\State\AbstractAppStateProvider;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\CustomPosts\Routing\RequestNature;

class AppStateProvider extends AbstractAppStateProvider
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

    public function augment(array &$state): void
    {
        $nature = $state['nature'];
        $state['routing']['is-custompost'] = $nature === RequestNature::CUSTOMPOST;

        // Attributes needed to match the RouteModuleProcessor vars conditions
        if ($nature === RequestNature::CUSTOMPOST) {
            $customPostID = $state['routing']['queried-object-id'];
            $state['routing']['queried-object-post-type'] = $this->getCustomPostTypeAPI()->getCustomPostType($customPostID);
        }
    }
}
