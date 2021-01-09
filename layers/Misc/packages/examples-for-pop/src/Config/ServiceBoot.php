<?php

declare(strict_types=1);

namespace Leoloso\ExamplesForPoP\Config;

use PoP\API\Facades\PersistedQueryManagerFacade;
use PoP\API\PersistedQueries\PersistedQueryUtils;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\API\Facades\PersistedFragmentManagerFacade;

class ServiceBoot
{
    public static function beforeBoot()
    {
        // 'contentMesh' persisted fragments. Comment for testing
        // Initialization of parameters
        $githubRepo = $_REQUEST['githubRepo'] ?? 'leoloso/PoP';
        $weatherZone = $_REQUEST['weatherZone'] ?? 'MOZ028';
        $photoPage = $_REQUEST['photoPage'] ?? 1;
        // Fragment resolution
        $meshServicesPersistedFragment = <<<EOT
        echo([
            github: "https://api.github.com/repos/$githubRepo",
            weather: "https://api.weather.gov/zones/forecast/$weatherZone/forecast",
            photos: "https://picsum.photos/v2/list?page=$photoPage&limit=10"
        ])@meshServices
EOT;
        $meshServiceDataPersistedFragment = <<<EOT
        --meshServices|
        getAsyncJSON(getSelfProp(%self%, meshServices))@meshServiceData
EOT;
        $contentMeshPersistedFragment = <<<EOT
        --meshServiceData|
        echo([
            weatherForecast: extract(
                getSelfProp(%self%, meshServiceData),
                weather.properties.periods
            ),
            photoGalleryURLs: extract(
                getSelfProp(%self%, meshServiceData),
                photos.url
            ),
            githubMeta: echo([
                description: extract(
                    getSelfProp(%self%, meshServiceData),
                    github.description
                ),
                starCount: extract(
                    getSelfProp(%self%, meshServiceData),
                    github.stargazers_count
                )
            ])
        ])@contentMesh
EOT;
        // Inject the values into the service
        $translationAPI = TranslationAPIFacade::getInstance();
        $persistedFragmentManager = PersistedFragmentManagerFacade::getInstance();
        $persistedFragmentManager->add(
            'meshServices',
            PersistedQueryUtils::removeWhitespaces($meshServicesPersistedFragment),
            $translationAPI->__('Services required to create a \'content mesh\' for the application: GitHub data for a specific repository, weather data from the National Weather Service for a specific zone, and random photo data from Unsplash', 'examples-for-pop')
        );
        $persistedFragmentManager->add(
            'meshServiceData',
            PersistedQueryUtils::removeWhitespaces($meshServiceDataPersistedFragment),
            $translationAPI->__('Retrieve data from the mesh services. This fragment includes calling fragment --meshServices', 'examples-for-pop')
        );
        $persistedFragmentManager->add(
            'contentMesh',
            PersistedQueryUtils::removeWhitespaces($contentMeshPersistedFragment),
            $translationAPI->__('Retrieve data from the mesh services and create a \'content mesh\'. This fragment includes calling fragment --meshServiceData', 'examples-for-pop')
        );

        // Persisted queries
        $contentMeshPersistedQuery = <<<EOT
        --contentMesh
EOT;
        $userPropsPersistedQuery = <<<EOT
        users.
            id|
            name|
            url|
            posts.
                id|
                title|
                url
EOT;
        // Inject the values into the service
        $persistedQueryManager = PersistedQueryManagerFacade::getInstance();
        $persistedQueryManager->add(
            'contentMesh',
            PersistedQueryUtils::removeWhitespaces($contentMeshPersistedQuery),
            $translationAPI->__('Retrieve data from the mesh services and create a \'content mesh\'', 'examples-for-pop')
        );
        $persistedQueryManager->add(
            'userProps',
            PersistedQueryUtils::removeWhitespaces($userPropsPersistedQuery),
            $translationAPI->__('Pre-defined set of user properties', 'examples-for-pop')
        );

        /**
         * Watch out! The GraphqL queries will be parsed ALWAYS, for every request!
         * So it's not a good idea to add them... Should wait until adding
         * some other layer that parses the query only if it is requested
         * Check in class GraphQLPersistedQueryUtils.
         * @todo: Add layer to not process GraphQL query always
         */
//         // GraphQL fragments
//         $userPropsGraphQLPersistedFragment = <<<EOT
//         {
//             id
//             name
//             url
//         }
// EOT;
//         // Inject the values into the service
//         GraphQLPersistedQueryUtils::addPersistedFragment(
//             'userProps',
//             $userPropsGraphQLPersistedFragment,
//             $translationAPI->__('User properties', 'examples-for-pop')
//         );

//         // GraphQL queries
//         $userPropsGraphQLPersistedQuery = <<<EOT
//         query {
//             users {
//                 ...userProps
//                 posts {
//                     id
//                     title
//                     url
//                     comments {
//                         id
//                         date
//                         content
//                     }
//                 }
//             }
//         }

//         fragment userProps on User {
//             id
//             name
//             url
//         }
// EOT;
//         // Inject the values into the service
//         GraphQLPersistedQueryUtils::addPersistedQuery(
//             'userPostsComments',
//             $userPropsGraphQLPersistedQuery,
//             $translationAPI->__('User properties, posts and comments', 'examples-for-pop')
//         );
    }
}
