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

    final public function setUserAuthorization(UserAuthorizationInterface $userAuthorization): void
    {
        $this->userAuthorization = $userAuthorization;
    }
    final protected function getUserAuthorization(): UserAuthorizationInterface
    {
        /** @var UserAuthorizationInterface */
        return $this->userAuthorization ??= $this->instanceManager->getInstance(UserAuthorizationInterface::class);
    }
    final public function setRenderingHelpers(RenderingHelpers $renderingHelpers): void
    {
        $this->renderingHelpers = $renderingHelpers;
    }
    final protected function getRenderingHelpers(): RenderingHelpers
    {
        /** @var RenderingHelpers */
        return $this->renderingHelpers ??= $this->instanceManager->getInstance(RenderingHelpers::class);
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

        // Commented out Prettify
        // // $scriptSrc = 'https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js'
        // $mainPluginURL = \GatoGraphQL\GatoGraphQL\PluginApp::getMainPlugin()->getPluginURL();
        // $scriptSrc = $mainPluginURL . 'assets/js/vendors/code-prettify/run_prettify.js';
        // /**
        //  * Prettyprint the code
        //  */
        // $content .= sprintf(
        //     '<script src="%s"></script>',
        //     $scriptSrc
        // );

        /**
         * Using highlight.js
         *
         * @see https://highlightjs.org/usage/
         */
        $linkTagPlaceholder = '<link rel="stylesheet" href="%s">';
        $scriptTagPlaceholder = '<script src="%s"></script>';
        $mainPluginURL = PluginApp::getMainPlugin()->getPluginURL();
        $content .= sprintf(
            $linkTagPlaceholder,
            $mainPluginURL . 'assets/css/vendors/highlight-11.6.0/a11y-dark.min.css'
        );
        $content .= sprintf(
            $scriptTagPlaceholder,
            $mainPluginURL . 'assets/js/vendors/highlight-11.6.0/highlight.min.js'
        );
        $content .= sprintf(
            $scriptTagPlaceholder,
            $mainPluginURL . 'assets/js/vendors/highlight-11.6.0/languages/graphql.min.js'
        );
        $content .= sprintf(
            $scriptTagPlaceholder,
            $mainPluginURL . 'assets/js/vendors/highlight-11.6.0/languages/json.min.js'
        );
        $content .= sprintf(
            $scriptTagPlaceholder,
            $mainPluginURL . 'assets/js/vendors/highlight-11.6.0/languages/bash.min.js'
        );
        $content .= sprintf(
            $scriptTagPlaceholder,
            $mainPluginURL . 'assets/js/run_highlight.js'
        );

        return $content;
    }
}
