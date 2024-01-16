<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\InputObjectType;

use PoPCMSSchema\MediaMutations\Constants\MutationInputProperties;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\HTMLScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractOneofInputObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;

/**
 * @todo In addition to "html", support additional oneof properties for the mutation (eg: provide "blocks" for Gutenberg)
 */
class CommentAsOneofInputObjectTypeResolver extends AbstractOneofInputObjectTypeResolver
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

    public function getTypeName(): string
    {
        return 'CommentContentInput';
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
            MutationInputProperties::HTML => $this->__('Use HTML as content for the comment', 'media-mutations'),
            default => parent::getInputFieldDescription($inputFieldName),
        };
    }
}
