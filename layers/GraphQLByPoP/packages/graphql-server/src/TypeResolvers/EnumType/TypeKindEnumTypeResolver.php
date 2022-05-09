<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\TypeResolvers\EnumType;

use GraphQLByPoP\GraphQLServer\ObjectModels\TypeKinds;

class TypeKindEnumTypeResolver extends AbstractIntrospectionEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'TypeKind';
    }
    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            TypeKinds::SCALAR,
            TypeKinds::OBJECT,
            TypeKinds::INTERFACE,
            TypeKinds::UNION,
            TypeKinds::ENUM,
            TypeKinds::INPUT_OBJECT,
            TypeKinds::LIST,
            TypeKinds::NON_NULL,
        ];
    }
}
