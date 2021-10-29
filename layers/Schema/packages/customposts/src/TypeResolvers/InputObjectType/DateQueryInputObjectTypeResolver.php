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
    private ?DateScalarTypeResolver $dateScalarTypeResolver = null;

    public function setDateScalarTypeResolver(DateScalarTypeResolver $dateScalarTypeResolver): void
    {
        $this->dateScalarTypeResolver = $dateScalarTypeResolver;
    }
    protected function getDateScalarTypeResolver(): DateScalarTypeResolver
    {
        return $this->dateScalarTypeResolver ??= $this->instanceManager->getInstance(DateScalarTypeResolver::class);
    }

    public function getTypeName(): string
    {
        return 'DateQuery';
    }

    public function getInputObjectFieldNameTypeResolvers(): array
    {
        return [
            'after' => $this->getDateScalarTypeResolver(),
            'before' => $this->getDateScalarTypeResolver(),
        ];
    }
}
