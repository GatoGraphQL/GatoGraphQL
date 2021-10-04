<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPSchema\Posts\Constants\InputNames;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractCustomPostUpdateUserMetaValueMutationResolverBridge extends AbstractUpdateUserMetaValueMutationResolverBridge
{
    protected CustomPostTypeAPIInterface $customPostTypeAPI;

    #[Required]
    final public function autowireAbstractCustomPostUpdateUserMetaValueMutationResolverBridge(
        CustomPostTypeAPIInterface $customPostTypeAPI,
    ): void {
        $this->customPostTypeAPI = $customPostTypeAPI;
    }

    protected function getRequestKey()
    {
        return InputNames::POST_ID;
    }
}
