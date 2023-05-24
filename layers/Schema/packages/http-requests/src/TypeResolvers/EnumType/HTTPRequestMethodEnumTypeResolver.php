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
    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            HTTPRequestMethodEnum::GET,
            HTTPRequestMethodEnum::POST,
            HTTPRequestMethodEnum::PUT,
            HTTPRequestMethodEnum::DELETE,
            HTTPRequestMethodEnum::PATCH,
            HTTPRequestMethodEnum::HEAD,
            HTTPRequestMethodEnum::OPTIONS,
        ];
    }
}
