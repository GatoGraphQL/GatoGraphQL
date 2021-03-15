<?php

declare(strict_types=1);

namespace Leoloso\ExamplesForPoP\Container\CompilerPasses;

use PoP\API\PersistedQueries\PersistedFragmentManagerInterface;
use PoP\API\PersistedQueries\PersistedQueryUtils;
use PoP\Root\Container\CompilerPasses\AbstractCompilerPass;
use PoP\Root\Container\ContainerBuilderWrapperInterface;
use PoP\Translation\Facades\SystemTranslationAPIFacade;

class ConfigurePersistedFragmentCompilerPass extends AbstractCompilerPass
{
    /**
     * GraphQL persisted query for Introspection query
     */
    protected function doProcess(ContainerBuilderWrapperInterface $containerBuilderWrapper): void
    {
        // 'contentMesh' persisted fragments
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
        /**
         * Watch out: Symfony DI uses %...% for parameters,
         * so we must escape the dollar sign when doing %self%, or we'll
         * get an error:
         * "PHP Fatal error:  Uncaught Symfony\\Component\\DependencyInjection\\Exception\\InvalidArgumentException: The parameter "self" must be defined."
         * @see https://symfony.com/doc/2.2/components/dependency_injection/parameters.html#parameters-in-configuration-files
         */
        $meshServiceDataPersistedFragment = <<<EOT
        --meshServices|
        getAsyncJSON(getSelfProp(%%self%%, meshServices))@meshServiceData
EOT;
        $contentMeshPersistedFragment = <<<EOT
        --meshServiceData|
        echo([
            weatherForecast: extract(
                getSelfProp(%%self%%, meshServiceData),
                weather.properties.periods
            ),
            photoGalleryURLs: extract(
                getSelfProp(%%self%%, meshServiceData),
                photos.url
            ),
            githubMeta: echo([
                description: extract(
                    getSelfProp(%%self%%, meshServiceData),
                    github.description
                ),
                starCount: extract(
                    getSelfProp(%%self%%, meshServiceData),
                    github.stargazers_count
                )
            ])
        ])@contentMesh
EOT;
        // Inject the values into the service
        $translationAPI = SystemTranslationAPIFacade::getInstance();
        $persistedFragmentManagerDefinition = $containerBuilderWrapper->getDefinition(PersistedFragmentManagerInterface::class);
        $persistedFragmentManagerDefinition->addMethodCall(
            'addPersistedFragment',
            [
                'meshServices',
                PersistedQueryUtils::removeWhitespaces($meshServicesPersistedFragment),
                $translationAPI->__('Services required to create a \'content mesh\' for the application: GitHub data for a specific repository, weather data from the National Weather Service for a specific zone, and random photo data from Unsplash', 'examples-for-pop')
            ]
        );
        $persistedFragmentManagerDefinition->addMethodCall(
            'addPersistedFragment',
            [
                'meshServiceData',
                PersistedQueryUtils::removeWhitespaces($meshServiceDataPersistedFragment),
                $translationAPI->__('Retrieve data from the mesh services. This fragment includes calling fragment --meshServices', 'examples-for-pop')
            ]
        );

        $persistedFragmentManagerDefinition->addMethodCall(
            'addPersistedFragment',
            [
                'contentMesh',
                PersistedQueryUtils::removeWhitespaces($contentMeshPersistedFragment),
                $translationAPI->__('Retrieve data from the mesh services and create a \'content mesh\'. This fragment includes calling fragment --meshServiceData', 'examples-for-pop')
            ]
        );
    }
}
