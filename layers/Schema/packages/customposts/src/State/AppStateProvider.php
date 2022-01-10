<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\State;

use PoP\Root\State\AbstractAppStateProvider;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPSchema\CustomPosts\Routing\RouteNatures;

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
        $state['routing-state']['is-custompost'] = $nature === RouteNatures::CUSTOMPOST;

        // Attributes needed to match the RouteModuleProcessor vars conditions
        if ($nature === RouteNatures::CUSTOMPOST) {
            $customPostID = $state['routing-state']['queried-object-id'];
            $state['routing-state']['queried-object-post-type'] = $this->getCustomPostTypeAPI()->getCustomPostType($customPostID);
        }
    }
}
