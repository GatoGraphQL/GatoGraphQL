<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\TypeResolvers\ScalarType;

/**
 * GraphQL Custom Scalar
 *
 * @see https://spec.graphql.org/draft/#sec-Scalars.Custom-Scalars
 */
class URIScalarTypeResolver extends AbstractURIScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'URI';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('URI scalar, such as https://mysite.com/my-fabulous-page', 'schema-commons');
    }

    public function getSpecifiedByURL(): ?string
    {
        return 'https://url.spec.whatwg.org/#url-representation';
    }
}
