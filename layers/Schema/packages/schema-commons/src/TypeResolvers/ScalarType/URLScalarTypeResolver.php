<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ScalarType;

/**
 * GraphQL Custom Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Custom-Scalars
 */
class URLScalarTypeResolver extends AbstractURIScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'URL';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('URL scalar, such as https://mysite.com/my-fabulous-page', 'component-model');
    }

    public function getSpecifiedByURL(): ?string
    {
        return 'https://url.spec.whatwg.org/#url-representation';
    }
}
