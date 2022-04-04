<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\Constants;

class BlockConstants
{
    /**
     * When saving the field (and its type) in the DB, the format is "typeNamespacedName.fieldName"
     */
    public final const TYPE_FIELD_SEPARATOR_FOR_DB = '.';
    /**
     * When showing the field (and its type) to the user, the format is "typeName.fieldName"
     */
    public final const TYPE_FIELD_SEPARATOR_FOR_PRINT = '/';
}
