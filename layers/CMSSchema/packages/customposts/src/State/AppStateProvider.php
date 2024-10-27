<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\State;

use PoP\Root\State\AbstractAppStateProvider;
use PoPCMSSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPCMSSchema\CustomPosts\Routing\RequestNature;

class AppStateProvider extends AbstractAppStateProvider
{
    private ?CustomPostTypeAPIInterface $customPostTypeAPI = null;

    final protected function getCustomPostTypeAPI(): CustomPostTypeAPIInterface
    {
        if ($this->customPostTypeAPI === null) {
            /** @var CustomPostTypeAPIInterface */
            $customPostTypeAPI = $this->instanceManager->getInstance(CustomPostTypeAPIInterface::class);
            $this->customPostTypeAPI = $customPostTypeAPI;
        }
        return $this->customPostTypeAPI;
    }

    /**
     * @param array<string,mixed> $state
     */
    public function augment(array &$state): void
    {
        $nature = $state['nature'];
        $state['routing']['is-custompost'] = $nature === RequestNature::CUSTOMPOST;

        // Attributes needed to match the ComponentRoutingProcessor vars conditions
        if ($nature === RequestNature::CUSTOMPOST) {
            $customPostID = $state['routing']['queried-object-id'];
            $state['routing']['queried-object-post-type'] = $this->getCustomPostTypeAPI()->getCustomPostType($customPostID);
        }
    }
}
