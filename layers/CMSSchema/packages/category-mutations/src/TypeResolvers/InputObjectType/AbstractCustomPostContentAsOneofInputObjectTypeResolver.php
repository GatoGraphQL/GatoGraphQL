<?php

declare(strict_types=1);

namespace PoPCMSSchema\CategoryMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CategoryMutations\Constants\MutationInputProperties;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\HTMLScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

abstract class AbstractCustomPostContentAsOneofInputObjectTypeResolver extends AbstractOneofInputObjectTypeResolver
{
    private ?HTMLScalarTypeResolver $htmlScalarTypeResolver = null;

    final public function setHTMLScalarTypeResolver(HTMLScalarTypeResolver $htmlScalarTypeResolver): void
    {
        $this->htmlScalarTypeResolver = $htmlScalarTypeResolver;
    }
    final protected function getHTMLScalarTypeResolver(): HTMLScalarTypeResolver
    {
        if ($this->htmlScalarTypeResolver === null) {
            /** @var HTMLScalarTypeResolver */
            $htmlScalarTypeResolver = $this->instanceManager->getInstance(HTMLScalarTypeResolver::class);
            $this->htmlScalarTypeResolver = $htmlScalarTypeResolver;
        }
        return $this->htmlScalarTypeResolver;
    }

    protected function isOneInputValueMandatory(): bool
    {
        return false;
    }

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getInputFieldNameTypeResolvers(): array
    {
        return [
            MutationInputProperties::HTML => $this->getHTMLScalarTypeResolver(),
        ];
    }

    public function getInputFieldDescription(string $inputFieldName): ?string
    {
        return match ($inputFieldName) {
            MutationInputProperties::HTML => $this->__('Use HTML as content for the category', 'category-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
