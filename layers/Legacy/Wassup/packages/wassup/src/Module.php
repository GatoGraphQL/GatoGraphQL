<?php

declare(strict_types=1);

namespace PoPSitesWassup\Wassup;

use PoP\Root\Module\AbstractComponent;

/**
 * Initialize component
 */
class Module extends AbstractComponent
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
                \GraphQLByPoP\GraphQLServer\Module::class,
                \PoPAPI\RESTAPI\Module::class,
                \PoP\TraceTools\Module::class,
                \PoPWPSchema\BlockMetadataWP\Module::class,
                \PoPSchema\CDNDirective\Module::class,
                \PoPSchema\ConvertCaseDirectives\Module::class,
                \PoPCMSSchema\CustomPostMediaWP\Module::class,
                \PoPCMSSchema\GoogleTranslateDirectiveForCustomPosts\Module::class,
                \PoPCMSSchema\PagesWP\Module::class,
                \PoPCMSSchema\PostsWP\Module::class,
                \PoPCMSSchema\PostTagsWP\Module::class,
                \PoPCMSSchema\TaxonomyQueryWP\Module::class,
                \PoPCMSSchema\UserRolesACL\Module::class,
                \PoPCMSSchema\UserRolesWP\Module::class,
                \PoPCMSSchema\UserStateWP\Module::class,
                \PoPCMSSchema\PostMutations\Module::class,
                \PoPCMSSchema\CustomPostMediaMutationsWP\Module::class,
                \PoPCMSSchema\PostTagMutationsWP\Module::class,
                \PoPCMSSchema\PostCategoryMutationsWP\Module::class,
                \PoPCMSSchema\CommentMutationsWP\Module::class,
                \PoPCMSSchema\UserStateMutationsWP\Module::class,
                \PoPCMSSchema\PostCategoriesWP\Module::class,
                \PoPCMSSchema\MenusWP\Module::class,
                \PoPCMSSchema\SettingsWP\Module::class,

                \PoPSchema\NotificationsWP\Module::class,
                \PoPSchema\HighlightsWP\Module::class,
                // Moved to outside repo
                // \PoPCMSSchema\LocationPostsWP\Module::class,
                \PoPSchema\StancesWP\Module::class,
            ],
            $skipLoadingUnmigratedComponents ? [] : [
                // These ones must have their ModuleProcessors defined as services
                \PoPSchema\EverythingElseWP\Module::class,
                \PoP\SiteWP\Module::class,
                \PoP\SPA\Module::class,
                \PoPSitesWassup\PostMutations\Module::class,
                \PoPSitesWassup\HighlightMutations\Module::class,
                \PoPSitesWassup\StanceMutations\Module::class,
                \PoPSitesWassup\CommentMutations\Module::class,
                \PoPSitesWassup\SystemMutations\Module::class,
                \PoPSitesWassup\GravityFormsMutations\Module::class,
                \PoPSitesWassup\ContactUsMutations\Module::class,
                \PoPSitesWassup\ContactUserMutations\Module::class,
                \PoPSitesWassup\NewsletterMutations\Module::class,
                \PoPSitesWassup\FlagMutations\Module::class,
                \PoPSitesWassup\ShareMutations\Module::class,
                \PoPSitesWassup\VolunteerMutations\Module::class,
                // Moved to outside repo
                // \PoPCMSSchema\EventMutationsWPEM\Module::class,
                // \PoPSitesWassup\EventMutations\Module::class,
                // \PoPSitesWassup\LocationMutations\Module::class,
                // \PoPSitesWassup\LocationPostMutations\Module::class,
                // \PoPSitesWassup\EventLinkMutations\Module::class,
                // \PoPSitesWassup\LocationPostLinkMutations\Module::class,
                \PoPSitesWassup\PostLinkMutations\Module::class,
                \PoPSitesWassup\NotificationMutations\Module::class,
                \PoPSitesWassup\SocialNetworkMutations\Module::class,
                \PoPSitesWassup\UserStateMutations\Module::class,
                \PoPSitesWassup\EverythingElseMutations\Module::class,
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
