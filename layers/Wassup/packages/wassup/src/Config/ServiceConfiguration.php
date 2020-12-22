<?php

declare(strict_types=1);

namespace PoPSitesWassup\Wassup\Config;

use PoP\Root\Component\PHPServiceConfigurationTrait;
use PoP\ComponentModel\Container\ContainerBuilderUtils;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoPSitesWassup\ShareMutations\MutationResolverBridges\ShareComponentMutationResolverBridge;
use PoPSitesWassup\ContactUsMutations\MutationResolverBridges\ContactUsComponentMutationResolverBridge;
use PoPSitesWassup\FlagMutations\MutationResolverBridges\FlagCustomPostComponentMutationResolverBridge;
use PoPSitesWassup\VolunteerMutations\MutationResolverBridges\VolunteerComponentMutationResolverBridge;
use PoPSitesWassup\ContactUserMutations\MutationResolverBridges\ContactUserComponentMutationResolverBridge;
use PoPSitesWassup\Wassup\MutationResolverBridges\GravityFormsNewsletterUnsubscriptionMutationResolverBridge;
use PoPSitesWassup\GravityFormsMutations\MutationResolverBridges\GravityFormsAddEntryToFormMutationResolverBridge;
use PoPSitesWassup\NewsletterMutations\MutationResolverBridges\NewsletterSubscriptionComponentMutationResolverBridge;
use PoPSitesWassup\NewsletterMutations\MutationResolverBridges\NewsletterUnsubscriptionComponentMutationResolverBridge;

class ServiceConfiguration
{
    use PHPServiceConfigurationTrait;

    protected static function configure(): void
    {
        ContainerBuilderUtils::injectValuesIntoService(
            InstanceManagerInterface::class,
            'overrideClass',
            ContactUsComponentMutationResolverBridge::class,
            GravityFormsAddEntryToFormMutationResolverBridge::class
        );

        ContainerBuilderUtils::injectValuesIntoService(
            InstanceManagerInterface::class,
            'overrideClass',
            ContactUsComponentMutationResolverBridge::class,
            GravityFormsAddEntryToFormMutationResolverBridge::class
        );

        ContainerBuilderUtils::injectValuesIntoService(
            InstanceManagerInterface::class,
            'overrideClass',
            ContactUserComponentMutationResolverBridge::class,
            GravityFormsAddEntryToFormMutationResolverBridge::class
        );

        ContainerBuilderUtils::injectValuesIntoService(
            InstanceManagerInterface::class,
            'overrideClass',
            FlagCustomPostComponentMutationResolverBridge::class,
            GravityFormsAddEntryToFormMutationResolverBridge::class
        );

        ContainerBuilderUtils::injectValuesIntoService(
            InstanceManagerInterface::class,
            'overrideClass',
            VolunteerComponentMutationResolverBridge::class,
            GravityFormsAddEntryToFormMutationResolverBridge::class
        );

        ContainerBuilderUtils::injectValuesIntoService(
            InstanceManagerInterface::class,
            'overrideClass',
            ShareComponentMutationResolverBridge::class,
            GravityFormsAddEntryToFormMutationResolverBridge::class
        );

        ContainerBuilderUtils::injectValuesIntoService(
            InstanceManagerInterface::class,
            'overrideClass',
            NewsletterSubscriptionComponentMutationResolverBridge::class,
            GravityFormsAddEntryToFormMutationResolverBridge::class
        );

        ContainerBuilderUtils::injectValuesIntoService(
            InstanceManagerInterface::class,
            'overrideClass',
            NewsletterUnsubscriptionComponentMutationResolverBridge::class,
            GravityFormsNewsletterUnsubscriptionMutationResolverBridge::class
        );
    }
}
