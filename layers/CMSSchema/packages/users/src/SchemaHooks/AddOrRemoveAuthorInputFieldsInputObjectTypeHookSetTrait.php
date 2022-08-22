<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\SchemaHooks;

use PoP\ComponentModel\TypeResolvers\InputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\StringScalarTypeResolver;

trait AddOrRemoveAuthorInputFieldsInputObjectTypeHookSetTrait
{
    abstract protected function getIDScalarTypeResolver(): IDScalarTypeResolver;
    abstract protected function getStringScalarTypeResolver(): StringScalarTypeResolver;

    /**
     * @return array<string,InputTypeResolverInterface>
     */
    protected function getAuthorInputFieldNameTypeResolvers(): array
    {
        return [
            'authorIDs' => $this->getIDScalarTypeResolver(),
            'authorSlug' => $this->getStringScalarTypeResolver(),
            'excludeAuthorIDs' => $this->getIDScalarTypeResolver(),
        ];
    }
}
