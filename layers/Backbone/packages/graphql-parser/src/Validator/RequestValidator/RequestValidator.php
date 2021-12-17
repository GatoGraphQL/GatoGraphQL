<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Validator\RequestValidator;

use PoPBackbone\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoPBackbone\GraphQLParser\Execution\Interfaces\RequestInterface;

class RequestValidator implements RequestValidatorInterface
{
    public function validate(RequestInterface $request): void
    {
        $this->assertFragmentReferencesValid($request);
        $this->assetFragmentsUsed($request);
        $this->assertAllVariablesExists($request);
        $this->assertAllVariablesUsed($request);
    }

    private function assetFragmentsUsed(RequestInterface $request)
    {
        foreach ($request->getFragmentReferences() as $fragmentReference) {
            $request->getFragment($fragmentReference->getName())->setUsed(true);
        }

        foreach ($request->getFragments() as $fragment) {
            if (!$fragment->isUsed()) {
                throw new InvalidRequestException(sprintf('Fragment \'%s\' not used', $fragment->getName()), $fragment->getLocation());
            }
        }
    }

    private function assertFragmentReferencesValid(RequestInterface $request)
    {
        foreach ($request->getFragmentReferences() as $fragmentReference) {
            if (!$request->getFragment($fragmentReference->getName())) {
                throw new InvalidRequestException(sprintf('Fragment \'%s\' not defined in query', $fragmentReference->getName()), $fragmentReference->getLocation());
            }
        }
    }

    private function assertAllVariablesExists(RequestInterface $request)
    {
        foreach ($request->getVariableReferences() as $variableReference) {
            if (!$variableReference->getVariable()) {
                throw new InvalidRequestException(sprintf('Variable \'%s\' not exists', $variableReference->getName()), $variableReference->getLocation());
            }
        }
    }

    private function assertAllVariablesUsed(RequestInterface $request)
    {
        foreach ($request->getQueryVariables() as $queryVariable) {
            if (!$queryVariable->isUsed()) {
                throw new InvalidRequestException(sprintf('Variable \'%s\' not used', $queryVariable->getName()), $queryVariable->getLocation());
            }
        }
    }
}
