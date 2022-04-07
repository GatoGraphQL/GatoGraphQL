<?php

declare(strict_types=1);

namespace GraphQLAPI\WPFakerSchema\TypeAPIs;

trait TypeAPITrait
{
    protected function resolveEscSQL(string $string): string
    {
        return $string;
    }
}
