<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolvers;

use Exception;

abstract class AbstractTaggedMutationResolver extends AbstractMutationResolver
{
    private MutationResolverInterface $inputFieldMutationResolver;

    /**
     * @return array<string, MutationResolverInterface> Array of inputFieldName => MutationResolver
     */
    protected abstract function getMutationResolvers(): array;
    
    final protected function defineInputFieldName(string $inputFieldName): void
    {
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

    final public function executeMutation(array $form_data): mixed
    {
        return $this->inputFieldMutationResolver->executeMutation($form_data);
    }
    final public function validateErrors(array $form_data): array
    {
        return $this->inputFieldMutationResolver->validateErrors($form_data);
    }
    final public function validateWarnings(array $form_data): array
    {
        return $this->inputFieldMutationResolver->validateWarnings($form_data);
    }
    final public function getErrorType(): int
    {
        return $this->inputFieldMutationResolver->getErrorType();
    }
}
