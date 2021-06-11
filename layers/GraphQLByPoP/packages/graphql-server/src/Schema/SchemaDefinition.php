<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Schema;

class SchemaDefinition
{
    const TYPE_MIXED = 'Mixed';
    const TYPE_ID = 'ID';
    const TYPE_STRING = 'String';
    const TYPE_INT = 'Int';
    const TYPE_FLOAT = 'Float';
    const TYPE_BOOL = 'Boolean';
    const TYPE_DATE = 'Date';
    const TYPE_TIME = 'Time';
    const TYPE_OBJECT = 'Object';
    const TYPE_URL = 'URL';
    const TYPE_EMAIL = 'Email';
    const TYPE_IP = 'IP';
    const TYPE_ENUM = 'Enum';
    const TYPE_INPUT_OBJECT = 'InputObject';
}
