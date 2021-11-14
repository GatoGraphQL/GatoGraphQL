<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolvers;

use Exception;

abstract class AbstractTaggedMutationResolver extends AbstractMutationResolver
{
    private string $inputFieldName;
    private MutationResolverInterface $inputFieldMutationResolver;

    /**
     * @return array<string, MutationResolverInterface> Array of inputFieldName => MutationResolver
     */
    protected abstract function getMutationResolvers(): array;
    
    final protected function setInputFieldName(string $inputFieldName): void
    {
        $this->inputFieldName = $inputFieldName;
        $this->inputFieldMutationResolver = $this->getInputFieldMutationResolver($inputFieldName);
    }
    private function getInputFieldMutationResolver(string $inputFieldName): MutationResolverInterface
    {
        $inputFieldMutationResolver = $this->getMutationResolvers()[$inputFieldName] ?? null;
        if ($inputFieldMutationResolver === null) {
            throw new Exception(
                $this->getTranslationAPI()->__('There is not TaggedMutationResolver defined for input field \'%s\'', 'component-model'),
                $inputFieldName
            );
        }
        return $inputFieldMutationResolver;
    }

    final public function executeMutation(array $formData): mixed
    {
        return $this->inputFieldMutationResolver->executeMutation($formData[$this->inputFieldName]);
    }
    final public function validateErrors(array $formData): array
    {
        return $this->inputFieldMutationResolver->validateErrors($formData[$this->inputFieldName]);
    }
    final public function validateWarnings(array $formData): array
    {
        return $this->inputFieldMutationResolver->validateWarnings($formData[$this->inputFieldName]);
    }
    final public function getErrorType(): int
    {
        return $this->inputFieldMutationResolver->getErrorType();
    }
}
