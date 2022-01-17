<?php

declare(strict_types=1);

namespace PoPSitesWassup\Wassup;

use PoP\Root\Component\AbstractComponent;

/**
 * Initialize component
 */
class Component extends AbstractComponent
{
    /**
     * Classes from PoP components that must be initialized before this component
     *
     * @return string[]
     */
    public function getDependedComponentClasses(): array
    {
        /**
         * Comment Leo 17/03/2021:
         * All the ModuleProcessors for all the migrate-* packages
         * must be defined as services, otherwise we get a
         * "service not-defined" Exception, in
         * layers/Engine/packages/component-model/src/ItemProcessors/ItemProcessorManagerTrait.php:
         *   $instanceManager = InstanceManagerFacade::getInstance();
         *   $processorInstance = $instanceManager->getInstance($itemProcessorClass);
         *
         * Until we can do the migration, added a flag to not register
         * the components with issues, so the the webserver doesn't fail.
         */
        $skipLoadingUnmigratedComponents = false;
        return array_merge(
            [
                // These ones are working OK
                \GraphQLByPoP\GraphQLServer\Component::class,
                \PoP\RESTAPI\Component::class,
                \PoP\TraceTools\Component::class,
                \PoPWPSchema\BlockMetadataWP\Component::class,
                \PoPSchema\CDNDirective\Component::class,
                \PoPSchema\ConvertCaseDirectives\Component::class,
                \PoPCMSSchema\CustomPostMediaWP\Component::class,
                \PoPSchema\GoogleTranslateDirectiveForCustomPosts\Component::class,
                \PoPCMSSchema\PagesWP\Component::class,
                \PoPCMSSchema\PostsWP\Component::class,
                \PoPCMSSchema\PostTagsWP\Component::class,
                \PoPCMSSchema\TaxonomyQueryWP\Component::class,
                \PoPCMSSchema\UserRolesACL\Component::class,
                \PoPCMSSchema\UserRolesWP\Component::class,
                \PoPCMSSchema\UserStateWP\Component::class,
                \PoPCMSSchema\PostMutations\Component::class,
                \PoPCMSSchema\CustomPostMediaMutationsWP\Component::class,
                \PoPCMSSchema\PostTagMutationsWP\Component::class,
                \PoPCMSSchema\PostCategoryMutationsWP\Component::class,
                \PoPCMSSchema\CommentMutationsWP\Component::class,
                \PoPCMSSchema\UserStateMutationsWP\Component::class,
                \PoPCMSSchema\PostCategoriesWP\Component::class,
                \PoPCMSSchema\MenusWP\Component::class,
                \PoPCMSSchema\SettingsWP\Component::class,

                \PoPSchema\NotificationsWP\Component::class,
                \PoPSchema\HighlightsWP\Component::class,
                // Moved to outside repo
                // \PoPCMSSchema\LocationPostsWP\Component::class,
                \PoPSchema\StancesWP\Component::class,
            ],
            $skipLoadingUnmigratedComponents ? [] : [
                // These ones must have their ModuleProcessors defined as services
                \PoPSchema\EverythingElseWP\Component::class,
                \PoP\SiteWP\Component::class,
                \PoP\SPA\Component::class,
                \PoPSitesWassup\PostMutations\Component::class,
                \PoPSitesWassup\HighlightMutations\Component::class,
                \PoPSitesWassup\StanceMutations\Component::class,
                \PoPSitesWassup\CommentMutations\Component::class,
                \PoPSitesWassup\SystemMutations\Component::class,
                \PoPSitesWassup\GravityFormsMutations\Component::class,
                \PoPSitesWassup\ContactUsMutations\Component::class,
                \PoPSitesWassup\ContactUserMutations\Component::class,
                \PoPSitesWassup\NewsletterMutations\Component::class,
                \PoPSitesWassup\FlagMutations\Component::class,
                \PoPSitesWassup\ShareMutations\Component::class,
                \PoPSitesWassup\VolunteerMutations\Component::class,
                // Moved to outside repo
                // \PoPCMSSchema\EventMutationsWPEM\Component::class,
                // \PoPSitesWassup\EventMutations\Component::class,
                // \PoPSitesWassup\LocationMutations\Component::class,
                // \PoPSitesWassup\LocationPostMutations\Component::class,
                // \PoPSitesWassup\EventLinkMutations\Component::class,
                // \PoPSitesWassup\LocationPostLinkMutations\Component::class,
                \PoPSitesWassup\PostLinkMutations\Component::class,
                \PoPSitesWassup\NotificationMutations\Component::class,
                \PoPSitesWassup\SocialNetworkMutations\Component::class,
                \PoPSitesWassup\UserStateMutations\Component::class,
                \PoPSitesWassup\EverythingElseMutations\Component::class,
            ]
        );
    }

    /**
     * Initialize services
     *
     * @param array<string, mixed> $configuration
     * @param string[] $skipSchemaComponentClasses
     */
    protected function initializeContainerServices(
        bool $skipSchema,
        array $skipSchemaComponentClasses,
    ): void {
        $this->initServices(dirname(__DIR__));
        $this->initServices(dirname(__DIR__), '/Overrides');
        $this->initSchemaServices(dirname(__DIR__), $skipSchema);
    }
}
