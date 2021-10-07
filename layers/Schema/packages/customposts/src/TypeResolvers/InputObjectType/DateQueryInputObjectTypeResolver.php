<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\TypeResolvers\InputObjectType;

use PoP\ComponentModel\TypeResolvers\InputObjectType\AbstractInputObjectTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\DateScalarTypeResolver;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * @todo Keep working on it
 */
class DateQueryInputObjectTypeResolver extends AbstractInputObjectTypeResolver
{
    protected DateScalarTypeResolver $dateScalarTypeResolver;

    #[Required]
    final public function autowireDateQueryInputObjectTypeResolver(
        DateScalarTypeResolver $dateScalarTypeResolver,
    ): void {
        $this->dateScalarTypeResolver = $dateScalarTypeResolver;
    }

    public function getTypeName(): string
    {
        return 'DateQuery';
    }

    public function getInputObjectFieldNameTypeResolvers(): array
    {
        return [
            'after' => $this->dateScalarTypeResolver,
            'before' => $this->dateScalarTypeResolver,
        ];
    }
}
