<?php

declare(strict_types=1);

namespace PoPCMSSchema\Pages\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\FilterInputs\FilterInputInterface;
use PoPCMSSchema\CustomPosts\TypeResolvers\InputObjectType\AbstractCustomPostByOneofInputObjectTypeResolver;
use PoPCMSSchema\SchemaCommons\FilterInputs\PathOrPathsFilterInput;

class PageByOneofInputObjectTypeResolver extends AbstractCustomPostByOneofInputObjectTypeResolver
{
    private ?PathOrPathsFilterInput $pathOrPathsFilterInput = null;

    final protected function getPathOrPathsFilterInput(): PathOrPathsFilterInput
    {
        if ($this->pathOrPathsFilterInput === null) {
            /** @var PathOrPathsFilterInput */
            $pathOrPathsFilterInput = $this->instanceManager->getInstance(PathOrPathsFilterInput::class);
            $this->pathOrPathsFilterInput = $pathOrPathsFilterInput;
        }
        return $this->pathOrPathsFilterInput;
    }

    public function getTypeName(): string
    {
        return 'PageByInput';
    }

    protected function getTypeDescriptionCustomPostEntity(): string
    {
        return $this->__('a page', 'pages');
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
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

    public function getInputFieldFilterInput(string $inputFieldName): ?FilterInputInterface
    {
        return match ($inputFieldName) {
            'path' => $this->getPathOrPathsFilterInput(),
            default => parent::getInputFieldFilterInput($inputFieldName),
        };
    }
}
