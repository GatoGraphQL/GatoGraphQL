<?php

declare(strict_types=1);

namespace PoP\APIClients;

use PoP\ComponentModel\Configuration\RequestHelpers;

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
    abstract protected function getComponentBaseDir(): string;
    /**
     * Base URL
     */
    protected function getComponentBaseURL(): ?string
    {
        return null;
    }
    /**
     * Endpoint URL
     */
    abstract protected function getEndpointURLOrURLPath(): string;

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
        $endpoint = $this->getEndpointURLOrURLPath();

        // Maybe enable XDebug
        $endpoint = RequestHelpers::maybeAddParamToDebugRequest($endpoint);

        /**
         * Must remove the protocol, or we might get an error with status 406
         * @see https://github.com/leoloso/PoP/issues/436
         */
        $endpoint = preg_replace('#^https?:#', '', $endpoint);
        // // If namespaced, add /?use_namespace=1 to the endpoint
        // /** @var ComponentModelComponentConfiguration */
        // $componentConfiguration = \PoP\Root\App::getComponent(ComponentModelComponent::class)->getConfiguration();
        // if ($componentConfiguration->mustNamespaceTypes()) {
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

    /**
     * If the endpoint for the client is requested, print the client and exit
     */
    protected function executeEndpoint(): void
    {
        echo $this->getClientHTML();
        die;
    }
}
