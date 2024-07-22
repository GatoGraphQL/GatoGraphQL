<?php

declare(strict_types=1);

namespace PoPAPI\APIClients;

use PoP\ComponentModel\Configuration\RequestHelpers;
use PoP\Root\Exception\ShouldNotHappenException;

trait ClientTrait
{
    private ?string $clientHTMLCache = null;

    /**
     * Relative Path
     */
    abstract protected function getClientRelativePath(): string;
    /**
     * JavaScript file name
     */
    abstract protected function getJSFilename(): string;
    /**
     * HTML file name
     */
    protected function getIndexFilename(): string
    {
        return 'index.html';
    }
    /**
     * Assets folder name
     */
    protected function getAssetDirname(): string
    {
        return 'assets';
    }
    /**
     * Base dir
     */
    abstract protected function getModuleBaseDir(): string;
    /**
     * Base URL
     */
    protected function getModuleBaseURL(): ?string
    {
        return null;
    }
    /**
     * Endpoint URL
     */
    abstract public function getEndpointURLOrURLPath(): ?string;

    abstract protected function __(string $text, string $domain = 'default'): string;

    /**
     * HTML to print the client
     */
    public function getClientHTML(): string
    {
        if ($this->clientHTMLCache !== null) {
            return $this->clientHTMLCache;
        }
        // Read from the static HTML files and replace their endpoints
        $assetRelativePath = $this->getClientRelativePath();
        $file = $this->getModuleBaseDir() . $assetRelativePath . '/' . $this->getIndexFilename();
        $fileContents = \file_get_contents($file, true);
        if ($fileContents === false) {
            throw new ShouldNotHappenException(
                sprintf(
                    $this->__('Cannot read the contents of file \'%s\''),
                    $file
                )
            );
        }
        $jsFileName = $this->getJSFilename();
        /**
         * Relative asset paths do not work, since the location of the JS/CSS file is
         * different than the URL under which the client is accessed.
         * Then add the URL to the plugin to all assets (they are all located under "assets/...")
         */
        if ($moduleBaseURL = $this->getModuleBaseURL()) {
            // The client could have several folders where to store the assets
            // GraphiQL Explorer loads under "/assets...", so the dirname starts with "/"
            // But otherwise it does not. So don't add "/" again if it already has
            $assetDirname = $this->getAssetDirname();
            $fileContents = \str_replace(
                '"' . $assetDirname . '/',
                '"' . \trim($moduleBaseURL, '/') . $assetRelativePath . (\str_starts_with($assetDirname, '/') ? '' : '/') . $assetDirname . '/',
                $fileContents
            );
        }

        // Can pass either URL or path under current domain
        $endpoint = $this->getEndpointURLOrURLPath();
        if ($endpoint === null) {
            throw new ShouldNotHappenException(
                $this->__('There is no endpoint for the client')
            );
        }

        // Add mandatory params from the request, and maybe enable XDebug
        $endpoint = RequestHelpers::addRequestParamsToEndpoint($endpoint);

        /**
         * Must remove the protocol, or we might get an error with status 406
         * @see https://github.com/GatoGraphQL/GatoGraphQL/issues/436
         *
         * @var string
         */
        $endpoint = preg_replace('#^https?:#', '', $endpoint);
        // // If namespaced, add /?use_namespace=1 to the endpoint
        // /** @var ComponentModelModuleConfiguration */
        // $moduleConfiguration = \PoP\Root\App::getModule(ComponentModelModule::class)->getConfiguration();
        // if ($moduleConfiguration->mustNamespaceTypes()) {
        //     $endpoint = GeneralUtils::addQueryArgs(
        //         [
        //             APIParams::USE_NAMESPACE => true,
        //         ],
        //         $endpoint
        //     );
        // }
        // Modify the endpoint, as a param to the script.
        // GraphiQL Explorer doesn't have other params. Otherwise it does, so check for "?"
        $jsFileHasParams = \str_contains($fileContents, '/' . $jsFileName . '?');
        $fileContents = \str_replace(
            '/' . $jsFileName . ($jsFileHasParams ? '?' : ''),
            '/' . $jsFileName . '?endpoint=' . urlencode($endpoint) . ($jsFileHasParams ? '&' : ''),
            $fileContents
        );

        $this->clientHTMLCache = $fileContents;
        return $this->clientHTMLCache;
    }
}
