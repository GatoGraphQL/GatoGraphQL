<?php

declare(strict_types=1);

namespace PoPSchema\HTTPRequests\TypeResolvers\EnumType;

use PoPSchema\HTTPRequests\Enums\HTTPRequestMethodEnum;
use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;

class HTTPRequestMethodEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'HTTPRequestMethodEnum';
    }
    public function getBackedEnumClass(): ?string
    {
        return HTTPRequestMethodEnum::class;
    }
}
