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
    public static function getDependedComponentClasses(): array
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
                \PoP\FunctionFields\Component::class,
                \PoP\RESTAPI\Component::class,
                \PoP\TraceTools\Component::class,
                \PoPSchema\BlockMetadataWP\Component::class,
                \PoPSchema\CDNDirective\Component::class,
                \PoPSchema\CommentMetaWP\Component::class,
                \PoPSchema\ConvertCaseDirectives\Component::class,
                \PoPSchema\CustomPostMediaWP\Component::class,
                \PoPSchema\CustomPostMetaWP\Component::class,
                \PoPSchema\GoogleTranslateDirectiveForCustomPosts\Component::class,
                \PoPSchema\PagesWP\Component::class,
                \PoPSchema\PostsWP\Component::class,
                \PoPSchema\PostTagsWP\Component::class,
                \PoPSchema\TaxonomyQueryWP\Component::class,
                \PoPSchema\UserMetaWP\Component::class,
                \PoPSchema\UserRolesACL\Component::class,
                \PoPSchema\UserRolesWP\Component::class,
                \PoPSchema\UserStateWP\Component::class,
                \PoPSchema\PostMutations\Component::class,
                \PoPSchema\CustomPostMediaMutationsWP\Component::class,
                \PoPSchema\CommentMutationsWP\Component::class,
                \PoPSchema\UserStateMutationsWP\Component::class,
                \PoPSchema\PostCategoriesWP\Component::class,

                \PoPSchema\NotificationsWP\Component::class,
                \PoPSchema\MenusWP\Component::class,
                \PoPSchema\HighlightsWP\Component::class,
                \PoPSchema\LocationPostsWP\Component::class,
                \PoPSchema\StancesWP\Component::class,
            ],
            $skipLoadingUnmigratedComponents ? [] : [
                // These ones must have their ModuleProcessors defined as services
                \PoPSchema\EventMutationsWPEM\Component::class,
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
                \PoPSitesWassup\EventMutations\Component::class,
                \PoPSitesWassup\LocationMutations\Component::class,
                \PoPSitesWassup\LocationPostMutations\Component::class,
                \PoPSitesWassup\PostLinkMutations\Component::class,
                \PoPSitesWassup\EventLinkMutations\Component::class,
                \PoPSitesWassup\LocationPostLinkMutations\Component::class,
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
    protected static function initializeContainerServices(
        array $configuration = [],
        bool $skipSchema = false,
        array $skipSchemaComponentClasses = []
    ): void {
        self::initServices(dirname(__DIR__));
        self::initServices(dirname(__DIR__), '/Overrides');
        self::initSchemaServices(dirname(__DIR__), $skipSchema);
    }
}
