<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\CustomPostMutations\Constants\MutationInputProperties;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\HTMLScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

/**
 * @todo In addition to "html", support additional oneof properties for the mutation (eg: provide "blocks" for Gutenberg)
 */
class CustomPostContentAsOneofInputObjectTypeResolver extends AbstractOneofInputObjectTypeResolver
{
    private ?HTMLScalarTypeResolver $htmlScalarTypeResolver = null;

    final public function setHTMLScalarTypeResolver(HTMLScalarTypeResolver $htmlScalarTypeResolver): void
    {
        $this->htmlScalarTypeResolver = $htmlScalarTypeResolver;
    }
    final protected function getHTMLScalarTypeResolver(): HTMLScalarTypeResolver
    {
        /** @var HTMLScalarTypeResolver */
        return $this->htmlScalarTypeResolver ??= $this->instanceManager->getInstance(HTMLScalarTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'CustomPostContentInput';
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
            MutationInputProperties::HTML => $this->__('Use HTML as content for the custom post', 'custompost-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
