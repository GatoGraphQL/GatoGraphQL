<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Mutation;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

abstract class AbstractFieldArgumentMutationDataProvider implements MutationDataProviderInterface
{
    public function __construct(
        protected FieldInterface $field
    ) {        
    }
}
