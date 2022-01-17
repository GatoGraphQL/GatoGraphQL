<?php

declare(strict_types=1);

namespace PoPSchema\Pages\TypeResolvers\InputObjectType;

use PoPSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostByInputObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputProcessors\FilterInputProcessor;

class PageByInputObjectTypeResolver extends AbstractCustomPostByInputObjectTypeResolver
{
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

    public function getInputFieldFilterInput(string $inputFieldName): ?array
    {
        return match ($inputFieldName) {
            'path' => [FilterInputProcessor::class, FilterInputProcessor::FILTERINPUT_PATH_OR_PATHS],
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
