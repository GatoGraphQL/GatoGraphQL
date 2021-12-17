<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Validator\RequestValidator;

use PoPBackbone\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoPBackbone\GraphQLParser\Execution\RequestInterface;

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
                throw new InvalidRequestException(
                    $this->getFragmentNotUsedErrorMessage($fragment->getName()),
                    $fragment->getLocation()
                );
            }
        }
    }

    protected function getFragmentNotUsedErrorMessage(string $fragmentName): string
    {
        return sprintf('Fragment \'%s\' not used', $fragmentName);
    }

    private function assertFragmentReferencesValid(RequestInterface $request)
    {
        foreach ($request->getFragmentReferences() as $fragmentReference) {
            if (!$request->getFragment($fragmentReference->getName())) {
                throw new InvalidRequestException(
                    $this->getFragmentNotDefinedInQueryErrorMessage($fragmentReference->getName()),
                    $fragmentReference->getLocation()
                );
            }
        }
    }

    protected function getFragmentNotDefinedInQueryErrorMessage(string $fragmentName): string
    {
        return sprintf('Fragment \'%s\' not defined in query', $fragmentName);
    }

    private function assertAllVariablesExists(RequestInterface $request)
    {
        foreach ($request->getVariableReferences() as $variableReference) {
            if (!$variableReference->getVariable()) {
                throw new InvalidRequestException(
                    $this->getVariableDoesNotExistErrorMessage($variableReference->getName()),
                    $variableReference->getLocation()
                );
            }
        }
    }

    protected function getVariableDoesNotExistErrorMessage(string $variableName): string
    {
        return sprintf('Variable \'%s\' does not exist', $variableName);
    }

    private function assertAllVariablesUsed(RequestInterface $request)
    {
        foreach ($request->getQueryVariables() as $queryVariable) {
            if (!$queryVariable->isUsed()) {
                throw new InvalidRequestException(
                    $this->getVariableNotUsedErrorMessage($queryVariable->getName()),
                    $queryVariable->getLocation()
                );
            }
        }
    }

    protected function getVariableNotUsedErrorMessage(string $variableName): string
    {
        return sprintf('Variable \'%s\' not used', $variableName);
    }
}
