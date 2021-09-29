<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use Symfony\Contracts\Service\Attribute\Required;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPSchema\Posts\Constants\InputNames;

abstract class AbstractCustomPostUpdateUserMetaValueMutationResolverBridge extends AbstractUpdateUserMetaValueMutationResolverBridge
{
    protected CustomPostTypeAPIInterface $customPostTypeAPI;

    #[Required]
    public function autowireAbstractCustomPostUpdateUserMetaValueMutationResolverBridge(
        CustomPostTypeAPIInterface $customPostTypeAPI,
    ): void {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }

    protected function getRequestKey()
    {
        return InputNames::POST_ID;
    }
}
