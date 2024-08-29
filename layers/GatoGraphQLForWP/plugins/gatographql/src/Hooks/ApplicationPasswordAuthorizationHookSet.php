<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Hooks;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\FeedbackItemProviders\ErrorFeedbackItemProvider;
use GatoGraphQL\GatoGraphQL\Request\PrematureRequestServiceInterface;
use PoP\ComponentModel\Engine\EngineHookNames;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\ComponentModel\Feedback\GeneralFeedback;
use PoP\RootWP\Exception\WPErrorDataProcessorTrait;
use PoP\Root\Hooks\AbstractHookSet;
use WP_Error;

use function add_action;
use function add_filter;

/**
 * Use:
 *
 *   curl -i \
 *     --user "{ USER }:{ APPLICATION PASSWORD}" \
 *     -X POST \
 *     -H "Content-Type: application/json" \
 *     -d '{"query": "{ id me { name } }"}' \
 *     https://mysite.com/graphql/
 */
class ApplicationPasswordAuthorizationHookSet extends AbstractHookSet
{
    use WPErrorDataProcessorTrait;

    private ?PrematureRequestServiceInterface $prematureRequestService = null;

    final public function setPrematureRequestService(PrematureRequestServiceInterface $prematureRequestService): void
    {
        $this->prematureRequestService = $prematureRequestService;
    }
    final protected function getPrematureRequestService(): PrematureRequestServiceInterface
    {
        if ($this->prematureRequestService === null) {
            /** @var PrematureRequestServiceInterface */
            $prematureRequestService = $this->instanceManager->getInstance(PrematureRequestServiceInterface::class);
            $this->prematureRequestService = $prematureRequestService;
        }
        return $this->prematureRequestService;
    }

    protected function init(): void
    {
        add_filter(
            'application_password_is_api_request',
            $this->isGraphQLAPIRequest(...),
            PHP_INT_MAX // Execute last
        );

        add_action(
            'application_password_failed_authentication',
            $this->handleError(...)
        );
    }

    /**
     * Check if requesting a GraphQL endpoint.
     *
     * Because the AppStateProviders have not been initialized yet,
     * we can't check ->doingJSON().
     *
     * As a workaround, retrieve the configuration for all GraphQL endpoints
     * (Single endpoint, custom endpoint, and persisted queries) and,
     * if any of them is enabled, check if the URL starts with their
     * path (even if that specific endpoint is disabled).
     *
     * Notice this checks only for the publicly-exposed GraphQL
     * endpoints (i.e. not for `wp-admin/edit.php?page=gatographql&action=execute_query`
     * or any of those).
     */
    public function isGraphQLAPIRequest(bool $isAPIRequest): bool
    {
        if ($isAPIRequest) {
            return $isAPIRequest;
        }

        return $this->getPrematureRequestService()->isPubliclyExposedGraphQLAPIRequest();
    }

    /**
     * If the user authentication fails, show the error message
     * in the GraphQL response
     */
    public function handleError(WP_Error $error): void
    {
        if (!$this->getPrematureRequestService()->isPubliclyExposedGraphQLAPIRequest()) {
            return;
        }

        App::addAction(
            EngineHookNames::PROCESS_AND_GENERATE_DATA_HELPER_CALCULATIONS,
            function () use ($error): void {
                $this->addErrorToFeedbackStore($error);
            }
        );
    }

    public function addErrorToFeedbackStore(WP_Error $error): void
    {
        $extensions = [];
        $errorData = $this->getWPErrorData($error);
        if ($errorData !== null) {
            $extensions['data'] = $errorData;
        }

        App::getFeedbackStore()->generalFeedbackStore->addError(
            new GeneralFeedback(
                new FeedbackItemResolution(
                    ErrorFeedbackItemProvider::class,
                    ErrorFeedbackItemProvider::E1,
                    [
                        $error->get_error_message(),
                    ]
                ),
                $extensions,
            )
        );
    }
}
