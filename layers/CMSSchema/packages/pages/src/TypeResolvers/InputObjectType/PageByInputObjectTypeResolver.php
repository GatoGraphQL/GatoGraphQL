<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\TypeResolvers\InputObjectType;

use PoP\ComponentModel\FilterInputProcessors\FilterInputProcessorInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostByInputObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\PathOrPathsFilterInputProcessor;

class PageByInputObjectTypeResolver extends AbstractCustomPostByInputObjectTypeResolver
{
    private ?PathOrPathsFilterInputProcessor $pathOrPathsFilterInputProcessor = null;

    final public function setPathOrPathsFilterInputProcessor(PathOrPathsFilterInputProcessor $pathOrPathsFilterInputProcessor): void
    {
        $this->pathOrPathsFilterInputProcessor = $pathOrPathsFilterInputProcessor;
    }
    final protected function getPathOrPathsFilterInputProcessor(): PathOrPathsFilterInputProcessor
    {
        return $this->pathOrPathsFilterInputProcessor ??= $this->instanceManager->getInstance(PathOrPathsFilterInputProcessor::class);
    }

    public function getTypeName(): string
    {
        return 'PageByInput';
    }

    protected function getTypeDescriptionCustomPostEntity(): string
    {
        return $this->__('a page', 'pages');
    }

    public function getInputFieldNameTypeResolvers(): array
    {
        return array_merge(
            parent::getInputFieldNameTypeResolvers(),
            [
                'path' => $this->getStringScalarTypeResolver(),
            ]
        );
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            'path' => $this->__('Query by page path', 'pages'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputProcessorInterface
    {
        return match ($inputFieldName) {
            'path' => $this->getPathOrPathsFilterInputProcessor(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
