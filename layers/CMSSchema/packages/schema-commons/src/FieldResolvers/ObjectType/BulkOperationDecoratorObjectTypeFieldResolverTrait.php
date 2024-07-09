<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\FieldResolvers\ObjectType;

use PoPCMSSchema\SchemaCommons\Constants\MutationInputProperties;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;

trait BulkOperationDecoratorObjectTypeFieldResolverTrait
{
    /**
     * @return array<string,InputTypeResolverInterface>
     */
    public function getBulkOperationFieldArgNameTypeResolvers(
        InputObjectTypeResolverInterface $inputObjectTypeResolver
    ): array
    {
        return [
            MutationInputProperties::INPUTS => $inputObjectTypeResolver,
        ];
    }
}
