<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\TypeResolvers\InputObjectType;

use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofQueryableInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\IncludeFilterInputProcessor;

class CommentByInputObjectTypeResolver extends AbstractOneofQueryableInputObjectTypeResolver
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?IncludeFilterInputProcessor $includeFilterInputProcessor = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    final public function setIncludeFilterInputProcessor(IncludeFilterInputProcessor $includeFilterInputProcessor): void
    {
        $this->includeFilterInputProcessor = $includeFilterInputProcessor;
    }
    final protected function getIncludeFilterInputProcessor(): IncludeFilterInputProcessor
    {
        return $this->includeFilterInputProcessor ??= $this->instanceManager->getInstance(IncludeFilterInputProcessor::class);
    }

    public function getTypeName(): string
    {
        return 'CommentByInput';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Oneof input to specify the property and data to fetch a comment', 'comments');
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'id' => $this->getIDScalarTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'id' => $this->__('Query by comment ID', 'comments'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputProcessorInterface
    {
        return match ($inputFieldName) {
            'id' => $this->getIncludeFilterInputProcessor(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
