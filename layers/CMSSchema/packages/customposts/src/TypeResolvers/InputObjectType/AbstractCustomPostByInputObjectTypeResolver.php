<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType;

use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofQueryableInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\IncludeFilterInputProcessor;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\SlugFilterInputProcessor;

abstract class AbstractCustomPostByInputObjectTypeResolver extends AbstractOneofQueryableInputObjectTypeResolver
{
    private ?IDScalarTypeResolver $idScalarTypeResolver = null;
    private ?StringScalarTypeResolver $stringScalarTypeResolver = null;
    private ?IncludeFilterInputProcessor $includeFilterInputProcessor = null;
    private ?SlugFilterInputProcessor $slugFilterInputProcessor = null;

    final public function setIDScalarTypeResolver(IDScalarTypeResolver $idScalarTypeResolver): void
    {
        $this->idScalarTypeResolver = $idScalarTypeResolver;
    }
    final protected function getIDScalarTypeResolver(): IDScalarTypeResolver
    {
        return $this->idScalarTypeResolver ??= $this->instanceManager->getInstance(IDScalarTypeResolver::class);
    }
    final public function setStringScalarTypeResolver(StringScalarTypeResolver $stringScalarTypeResolver): void
    {
        $this->stringScalarTypeResolver = $stringScalarTypeResolver;
    }
    final protected function getStringScalarTypeResolver(): StringScalarTypeResolver
    {
        return $this->stringScalarTypeResolver ??= $this->instanceManager->getInstance(StringScalarTypeResolver::class);
    }
    final public function setIncludeFilterInputProcessor(IncludeFilterInputProcessor $includeFilterInputProcessor): void
    {
        $this->includeFilterInputProcessor = $includeFilterInputProcessor;
    }
    final protected function getIncludeFilterInputProcessor(): IncludeFilterInputProcessor
    {
        return $this->includeFilterInputProcessor ??= $this->instanceManager->getInstance(IncludeFilterInputProcessor::class);
    }
    final public function setSlugFilterInputProcessor(SlugFilterInputProcessor $slugFilterInputProcessor): void
    {
        $this->slugFilterInputProcessor = $slugFilterInputProcessor;
    }
    final protected function getSlugFilterInputProcessor(): SlugFilterInputProcessor
    {
        return $this->slugFilterInputProcessor ??= $this->instanceManager->getInstance(SlugFilterInputProcessor::class);
    }

    public function getTypeDescription(): ?string
    {
        return sprintf(
            $this->__('Oneof input to specify the property and data to fetch %s', 'customposts'),
            $this->getTypeDescriptionCustomPostEntity()
        );
    }

    protected function getTypeDescriptionCustomPostEntity(): string
    {
        return $this->__('a custom post', 'customposts');
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            'id' => $this->getIDScalarTypeResolver(),
            'slug' => $this->getStringScalarTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'id' => $this->__('Query by custom post ID', 'customposts'),
            'slug' => $this->__('Query by custom post slug', 'customposts'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputProcessorInterface
    {
        return match ($inputFieldName) {
            'id' => $this->getIncludeFilterInputProcessor(),
            'slug' => $this->getSlugFilterInputProcessor(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
