<?php

declare(strict_types=1);

namespace PoP\APIClients;

use PoP\API\Configuration\Request;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\ComponentConfiguration as ComponentModelComponentConfiguration;

trait ClientTrait
{
    private ?string $clientHTMLCache = null;

    /**
     * Relative Path
     *
     * @return string
     */
    abstract protected function getClientRelativePath(): string;
    /**
     * JavaScript file name
     *
     * @return string
     */
    abstract protected function getJSFilename(): string;
    /**
     * HTML file name
     *
     * @return string
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
     *
     * @return string
     */
    abstract protected function getComponentBaseDir(): string;
    /**
     * Base URL
     *
     * @return string|null
     */
    protected function getComponentBaseURL(): ?string
    {
        return null;
    }
    /**
     * Endpoint URL
     *
     * @return string
     */
    abstract protected function getEndpointURL(): string;

    /**
     * HTML to print the client
     *
     * @return string
     */
    public function getClientHTML(): string
    {
        if ($this->clientHTMLCache !== null) {
            return $this->clientHTMLCache;
        }
        // Read from the static HTML files and replace their endpoints
        $assetRelativePath = $this->getClientRelativePath();
        $file = $this->getComponentBaseDir() . $assetRelativePath . '/' . $this->getIndexFilename();
        $fileContents = \file_get_contents($file, true);
        $jsFileName = $this->getJSFilename();
        /**
         * Relative asset paths do not work, since the location of the JS/CSS file is
         * different than the URL under which the client is accessed.
         * Then add the URL to the plugin to all assets (they are all located under "assets/...")
         */
        if ($componentBaseURL = $this->getComponentBaseURL()) {
            // The client could have several folders where to store the assets
            // GraphiQL Explorer loads under "/assets...", so the dirname starts with "/"
            // But otherwise it does not. So don't add "/" again if it already has
            $assetDirname = $this->getAssetDirname();
            $fileContents = \str_replace(
                '"' . $assetDirname . '/',
                '"' . \trim($componentBaseURL, '/') . $assetRelativePath . (\str_starts_with($assetDirname, '/') ? '' : '/') . $assetDirname . '/',
                $fileContents
            );
        }

        // Can pass either URL or path under current domain
        $endpointURL = $this->getEndpointURL();
        // // If namespaced, add /?use_namespace=1 to the endpoint
        // if (ComponentModelComponentConfiguration::namespaceTypesAndInterfaces()) {
        //     $endpointURL = GeneralUtils::addQueryArgs(
        //         [
        //             Request::URLPARAM_USE_NAMESPACE => true,
        //         ],
        //         $endpointURL
        //     );
        // }
        // Modify the endpoint, as a param to the script.
        // GraphiQL Explorer doesn't have other params. Otherwise it does, so check for "?"
        $jsFileHasParams = \str_contains($fileContents, '/' . $jsFileName . '?');
        $fileContents = \str_replace(
            '/' . $jsFileName . ($jsFileHasParams ? '?' : ''),
            '/' . $jsFileName . '?endpoint=' . urlencode($endpointURL) . ($jsFileHasParams ? '&' : ''),
            $fileContents
        );

        $this->clientHTMLCache = $fileContents;
        return $this->clientHTMLCache;
    }

    /**
     * If the endpoint for the client is requested, print the client and exit
     *
     * @return void
     */
    protected function executeEndpoint(): void
    {
        echo $this->getClientHTML();
        die;
    }
}
