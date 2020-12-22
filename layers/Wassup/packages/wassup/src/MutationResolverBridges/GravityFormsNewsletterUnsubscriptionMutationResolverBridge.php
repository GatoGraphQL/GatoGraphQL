<?php

declare(strict_types=1);

namespace PoPSitesWassup\Wassup\MutationResolverBridges;

use PoPSitesWassup\FormMutations\MutationResolverBridges\AbstractFormComponentMutationResolverBridge;
use PoPSitesWassup\Wassup\MutationResolvers\GravityFormsNewsletterUnsubscriptionMutationResolver;

class GravityFormsNewsletterUnsubscriptionMutationResolverBridge extends AbstractFormComponentMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return GravityFormsNewsletterUnsubscriptionMutationResolver::class;
    }
}
