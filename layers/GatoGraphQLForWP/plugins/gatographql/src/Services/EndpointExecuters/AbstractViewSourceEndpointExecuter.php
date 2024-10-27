<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Services\EndpointExecuters;

use GatoGraphQL\GatoGraphQL\Constants\RequestParams;
use GatoGraphQL\GatoGraphQL\PluginApp;
use GatoGraphQL\GatoGraphQL\Security\UserAuthorizationInterface;
use GatoGraphQL\GatoGraphQL\Services\EndpointExecuters\EndpointExecuterServiceTagInterface;
use GatoGraphQL\GatoGraphQL\Services\Helpers\RenderingHelpers;
use PoP\Root\App;
use WP_Post;

abstract class AbstractViewSourceEndpointExecuter extends AbstractCPTEndpointExecuter implements EndpointExecuterServiceTagInterface
{
    private ?UserAuthorizationInterface $userAuthorization = null;
    private ?RenderingHelpers $renderingHelpers = null;

    final protected function getUserAuthorization(): UserAuthorizationInterface
    {
        if ($this->userAuthorization === null) {
            /** @var UserAuthorizationInterface */
            $userAuthorization = $this->instanceManager->getInstance(UserAuthorizationInterface::class);
            $this->userAuthorization = $userAuthorization;
        }
        return $this->userAuthorization;
    }
    final protected function getRenderingHelpers(): RenderingHelpers
    {
        if ($this->renderingHelpers === null) {
            /** @var RenderingHelpers */
            $renderingHelpers = $this->instanceManager->getInstance(RenderingHelpers::class);
            $this->renderingHelpers = $renderingHelpers;
        }
        return $this->renderingHelpers;
    }

    protected function getView(): string
    {
        return RequestParams::VIEW_SOURCE;
    }

    public function executeEndpoint(): void
    {
        /** Add the excerpt, which is the description of the GraphQL query */
        \add_filter(
            'the_content',
            $this->maybeGetGraphQLQuerySourceContent(...)
        );
    }

    /**
     * Render the GraphQL Query CPT
     */
    public function maybeGetGraphQLQuerySourceContent(string $content): string
    {
        $customPost = App::getState(['routing', 'queried-object']);

        // Make sure there is a post (eg: it has not been deleted)
        if ($customPost !== null) {
            // Make sure the visitor can access the source
            $graphQLQuerySourceContent = $this->getGraphQLQuerySourceContent($content, $customPost);
            if ($graphQLQuerySourceContent === null) {
                return $this->getRenderingHelpers()->getUnauthorizedAccessHTMLMessage();
            }
            return $graphQLQuerySourceContent;
        }
        return $content;
    }

    /**
     * Render the GraphQL Query CPT
     */
    protected function getGraphQLQuerySourceContent(string $content, WP_Post $graphQLQueryPost): ?string
    {
        /**
         * Show only if the user has the right permission
         */
        if (!$this->getUserAuthorization()->canAccessSchemaEditor()) {
            return null;
        }

        $this->enqueueAssets();
        return $content;
    }

    /**
     * Enqueue assets (styles and scripts) needed to format the content
     */
    protected function enqueueAssets(): void
    {
        $mainPluginURL = PluginApp::getMainPlugin()->getPluginURL();
        $this->enqueueHighlightJSAssets($mainPluginURL);
    }

    /**
     * Enqueue highlight.js to prettyprint the code
     *
     * @see https://highlightjs.org/usage/
     */
    protected function enqueueHighlightJSAssets(string $mainPluginURL): void
    {
        wp_enqueue_style('highlight-theme', $mainPluginURL . 'assets/css/vendors/highlight-11.6.0/a11y-dark.min.css');
        wp_enqueue_script('highlight-script', $mainPluginURL . 'assets/js/vendors/highlight-11.6.0/highlight.min.js');
        wp_enqueue_script('highlight-language-graphql', $mainPluginURL . 'assets/js/vendors/highlight-11.6.0/languages/graphql.min.js');
        wp_enqueue_script('highlight-language-json', $mainPluginURL . 'assets/js/vendors/highlight-11.6.0/languages/json.min.js');
        wp_enqueue_script('highlight-language-bash', $mainPluginURL . 'assets/js/vendors/highlight-11.6.0/languages/bash.min.js');
        wp_enqueue_script('highlight-language-xml', $mainPluginURL . 'assets/js/vendors/highlight-11.6.0/languages/xml.min.js');
        wp_enqueue_script('highlight-language-diff', $mainPluginURL . 'assets/js/vendors/highlight-11.6.0/languages/diff.min.js');
        wp_enqueue_script('highlight-script-run', $mainPluginURL . 'assets/js/run_highlight.js');
    }
}
