<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;

abstract class AbstractUpdateUserMetaValueMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    abstract protected function getRequestKey();

    public function getFormData(): array
    {
        $form_data = array(
            'target_id' => $_GET[$this->getRequestKey()] ?? null,
        );

        return $form_data;
    }
}
