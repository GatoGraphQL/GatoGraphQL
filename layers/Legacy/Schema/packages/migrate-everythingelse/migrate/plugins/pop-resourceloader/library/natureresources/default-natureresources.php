<?php

use PoP\Root\Routing\RequestNature;
use PoPCMSSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPCMSSchema\CustomPosts\Routing\RequestNature as CustomPostRequestNature;
use PoPCMSSchema\Pages\Routing\RequestNature as PageRequestNature;
use PoPCMSSchema\PostCategories\Facades\PostCategoryTypeAPIFacade;
use PoPCMSSchema\PostTags\Facades\PostTagTypeAPIFacade;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\Tags\Routing\RequestNature as TagRequestNature;
use PoPCMSSchema\UserRoles\Facades\UserRoleTypeAPIFacade;
use PoPCMSSchema\Users\Facades\UserTypeAPIFacade;
use PoPCMSSchema\Users\Routing\RequestNature as UserRequestNature;

define('POP_RESOURCELOADERCONFIGURATION_HOME_STATIC', 'static');
define('POP_RESOURCELOADERCONFIGURATION_HOME_FEED', 'feed');

class PoP_ResourceLoader_NatureResources_DefaultResources extends PoP_ResourceLoader_NatureResources_ProcessorBase
{
    protected function maybeAddExtraVars($options, $nature, $ids = array())
    {

        // Organization: it must add together the resources for both "source=community" and "source=user"
        // Then, for the organization and community roles, we must set the extra \PoP\Root\App::getState('source') value
        // Add a hook to allow PoP Common User Roles to set it
        if ($extra_vars = \PoP\Root\App::applyFilters(
            'PoPThemeWassup_ResourceLoader_Hooks:extra-vars',
            array(),
            $nature,
            $ids
        )
        ) {
            $options['extra-vars'] = $extra_vars;
        }

        return $options;
    }

    public function addHomeResources(&$resources, $componentFilter, $options)
    {
        $nature = RequestNature::HOME;
        $options = $this->maybeAddExtraVars($options, $nature);

        // Home resources: there are 2 schemes:
        // 1. GetPoP: a single page
        // 2. MESYM: a feed of posts, with formats
        // Allow to select the right configuration through hooks
        $scheme = \PoP\Root\App::applyFilters(
            'PoPThemeWassup_ResourceLoader_HomeHooks:home-resources:scheme',
            POP_RESOURCELOADERCONFIGURATION_HOME_FEED
        );
        if ($scheme == POP_RESOURCELOADERCONFIGURATION_HOME_FEED) {
            PoP_ResourceLoaderProcessorUtils::addResourcesFromSettingsprocessors($componentFilter, $resources, $nature, array(), false, $options);
        } elseif ($scheme == POP_RESOURCELOADERCONFIGURATION_HOME_STATIC) {
            // Add a single item
            PoP_ResourceLoaderProcessorUtils::addResourcesFromCurrentVars($componentFilter, $resources, $nature, array(), false, array(), $options);
        }
    }

    public function add404Resources(&$resources, $componentFilter, $options)
    {
        $nature = RequestNature::NOTFOUND;
        $options = $this->maybeAddExtraVars($options, $nature);

        PoP_ResourceLoaderProcessorUtils::addResourcesFromCurrentVars($componentFilter, $resources, $nature, array(), false, array(), $options);
    }

    public function addTagResources(&$resources, $componentFilter, $options)
    {

        // Get any one tag from the DB
        $query = array(
            'limit' => 1,
            // 'fields' => 'ids',
        );
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
        if ($ids = $postTagTypeAPI->getTags($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
            $nature = TagRequestNature::TAG;
            $options = $this->maybeAddExtraVars($options, $nature, $ids);

            PoP_ResourceLoaderProcessorUtils::addResourcesFromSettingsprocessors($componentFilter, $resources, $nature, $ids, false, $options);
        }
    }

    public function addAuthorResources(&$resources, $componentFilter, $options)
    {
        $userTypeAPI = UserTypeAPIFacade::getInstance();
        // The author is a special case: different roles will have different configurations
        // However, we can't tell from the URL the role of that author (mesym.com/u/leo/ and mesym.com/u/mesym/)
        // So then, we gotta calculate the resources for both cases, and add them together
        // This way, loading any one author URL will load the resources needed for all different roles (Organization/Individual)
        $query = array(
            'limit' => 1,
            // 'fields' => 'ID',
        );

        // Roles: either use the one set in the application or, if it is empty, any role from any existing user on the DB
        $roles = gdRoles();
        if (empty($roles)) {
            // Being here, GD_ROLE_PROFILE is not defined. Then, simply get any random user from the DB, and use its corresponding configuration (the default layouts for all non-profile users (eg: "subscriber") will be used)
            $user_ids = $userTypeAPI->getUsers($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            $userRoleTypeAPI = UserRoleTypeAPIFacade::getInstance();
            /** @var string */
            $role = $userRoleTypeAPI->getTheUserRole($user_ids[0]);
            $roles = array($role);
            $user_role_combinations = array($roles);
        } else {
            $user_role_combinations = getUserRoleCombinations();
        }

        $ids = array();
        foreach ($user_role_combinations as $user_role_combination) {
            if ($role_ids = $userTypeAPI->getUsers(
                array_merge(
                    $query,
                    array(
                        'role-in' => $user_role_combination,
                        'role-not-in' => array_diff(
                            $roles,
                            $user_role_combination
                        ),
                    ),
                    [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]
                )
            )
            ) {
                $ids[] = $role_ids[0];
            }
        }

        if ($ids) {
            $nature = UserRequestNature::USER;
            $merge = true;
            $options = $this->maybeAddExtraVars($options, $nature, $ids);

            PoP_ResourceLoaderProcessorUtils::addResourcesFromSettingsprocessors($componentFilter, $resources, $nature, $ids, $merge, $options);
        }
    }

    public function addSingleResources(&$resources, $componentFilter, $options)
    {
        $nature = CustomPostRequestNature::CUSTOMPOST;
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();

        // Get one ID per category from the DB
        $ids = array();

        $query = array(
            'limit' => 1,
            // 'fields' => 'ids',
        );
        if (defined('POP_TAXONOMIES_INITIALIZED')) {
            $postCategoryTypeAPI = PostCategoryTypeAPIFacade::getInstance();
            $all_categories = $postCategoryTypeAPI->getCategories([], [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);

            // Allow to filter the categories.
            // This is needed so that Articles/Announcements/etc similar-configuration categories
            // can be generated only once
            $categories = \PoP\Root\App::applyFilters(
                'PoPThemeWassup_ResourceLoader_Hooks:single_resources:categories',
                $all_categories
            );
            foreach ($categories as $category) {
                if (
                    $post_ids = $customPostTypeAPI->getCustomPosts(
                        array_merge(
                            $query,
                            array(
                                'categories' => [$category],
                            ),
                            [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]
                        )
                    )
                ) {
                    $ids[] = $post_ids[0];
                }
            }

            // if (PoP_ResourceLoader_NatureResources_DefaultResources_Utils::add_nocategory_single_resources()) {

            //     // Also, add the configuration for one post without any category
            //     if ($post_ids = $customPostTypeAPI->getCustomPosts(array_merge(
            //         $query,
            //         array(
            //             'category__not_in' => $all_categories,
            //         )
            //     ))) {
            //         $ids[] = $post_ids[0];
            //     }
            // }

            // Make sure there are no duplicate $ids (eg: a same post having 2 categories, such as "posts" (parent) and "articles" (child))
            $ids = array_unique($ids);
        } else {
            $ids = $customPostTypeAPI->getCustomPosts(
                $query,
                [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]
            );
        }

        if ($ids) {
            $options = $this->maybeAddExtraVars($options, $nature, $ids);

            // Even though from different categories, posts may still have the same path
            // (Eg: posts/articles and posts/announcements may both have path "posts" only, if both parent category ("posts") and child category ("articles") were added to the post
            // Then, because these posts have the same path, their configuration must be merged together
            $paths = array();
            foreach ($ids as $id) {
                $post_path = \PoPCMSSchema\Posts\Engine_Utils::getCustomPostPath($id, true);
                $paths[$post_path] = $paths[$post_path] ?? array();
                $paths[$post_path][] = $id;
            }

            // All the ids with a unique path, can be merged all together using $merge=false
            // All the ids whose path is not unique, merge all those corresponding ids together using $merge=true
            $unique_path_ids = array();
            foreach ($paths as $post_path => $post_path_ids) {
                if (count($post_path_ids) == 1) {
                    $unique_path_ids[] =  $post_path_ids[0];
                } else {
                    $merge = true;
                    PoP_ResourceLoaderProcessorUtils::addResourcesFromSettingsprocessors($componentFilter, $resources, $nature, $post_path_ids, $merge, $options);
                }
            }

            if ($unique_path_ids) {
                $merge = false;
                PoP_ResourceLoaderProcessorUtils::addResourcesFromSettingsprocessors($componentFilter, $resources, $nature, $ids, $merge, $options);
            }
        }
    }

    public function addPageResources(&$resources, $componentFilter, $options)
    {
        $nature = PageRequestNature::PAGE;
        $options = $this->maybeAddExtraVars($options, $nature);

        PoP_ResourceLoaderProcessorUtils::addResourcesFromSettingsprocessors($componentFilter, $resources, $nature, array(), false, $options);
    }
}

/**
 * Initialize
 */
new PoP_ResourceLoader_NatureResources_DefaultResources();
