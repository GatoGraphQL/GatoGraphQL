<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPSchema\Posts\Constants\InputNames;

abstract class AbstractCustomPostUpdateUserMetaValueMutationResolverBridge extends AbstractUpdateUserMetaValueMutationResolverBridge
{
    protected CustomPostTypeAPIInterface $customPostTypeAPI;
    public function __construct(
        CustomPostTypeAPIInterface $customPostTypeAPI,
    ) {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }

    protected function getRequestKey()
    {
        return InputNames::POST_ID;
    }
}
