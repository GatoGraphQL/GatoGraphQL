<?php

declare(strict_types=1);

namespace PoPSchema\Users\SchemaHooks;

use PoP\Engine\TypeResolvers\ScalarType\IDScalarTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\StringScalarTypeResolver;

trait AddOrRemoveAuthorInputFieldsInputObjectTypeHookSetTrait
{
    abstract protected function getIDScalarTypeResolver(): IDScalarTypeResolver;
    abstract protected function getStringScalarTypeResolver(): StringScalarTypeResolver;

    protected function getAuthorInputFieldNameTypeResolvers(): array
    {
        return [
            'authorIDs' => $this->getIDScalarTypeResolver(),
            'authorSlug' => $this->getStringScalarTypeResolver(),
            'excludeAuthorIDs' => $this->getIDScalarTypeResolver(),
        ];
    }
}
