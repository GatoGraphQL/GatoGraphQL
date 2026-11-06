<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Hooks;

use GraphQLByPoP\GraphQLServer\Validation\RequestValidationServiceInterface;
use PoP\GraphQLParser\ExtendedSpec\Execution\ExecutableDocumentInterface;
use PoP\Root\App;
use PoP\Root\Constants\HookNames;
use PoP\Root\Hooks\AbstractHookSet;

/**
 * Validate the GraphQL query against the schema once
 * all the AppStateProviders have been executed (so that
 * the state they set, which the type resolvers may
 * depend on, is available) and, if it has request
 * errors, do not execute it.
 */
class RequestValidationHookSet extends AbstractHookSet
{
    private ?RequestValidationServiceInterface $requestValidationService = null;

    final protected function getRequestValidationService(): RequestValidationServiceInterface
    {
        if ($this->requestValidationService === null) {
            /** @var RequestValidationServiceInterface */
            $requestValidationService = $this->instanceManager->getInstance(RequestValidationServiceInterface::class);
            $this->requestValidationService = $requestValidationService;
        }
        return $this->requestValidationService;
    }

    protected function init(): void
    {
        App::addFilter(
            HookNames::APP_STATE_EXECUTED,
            $this->maybeValidateExecutableDocument(...)
        );
    }

    /**
     * @param array<string,mixed> $state
     * @return array<string,mixed>
     */
    public function maybeValidateExecutableDocument(array $state): array
    {
        /** @var ExecutableDocumentInterface|null */
        $executableDocument = $state['executable-document-ast'] ?? null;
        if ($executableDocument === null) {
            return $state;
        }
        if ($this->getRequestValidationService()->maybeValidateExecutableDocumentAgainstSchema($executableDocument)) {
            return $state;
        }
        $state['executable-document-ast'] = null;
        $state['does-api-query-have-errors'] = true;
        return $state;
    }
}
