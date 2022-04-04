<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleSettings;

class Properties
{
    public final const NAME = 'name';
    public final const INPUT = 'input';
    public final const TITLE = 'title';
    public final const DESCRIPTION = 'description';
    public final const POSSIBLE_VALUES = 'possibleValues';
    public final const CAN_BE_EMPTY = 'canBeEmpty';
    // Used for Select inputs
    public final const IS_MULTIPLE = 'isMultiple';
    // Used for Integers
    public final const MIN_NUMBER = 'minNumber';
    public final const TYPE = 'type';
    public final const TYPE_STRING = 'string';
    public final const TYPE_BOOL = 'bool';
    public final const TYPE_INT = 'int';
    public final const TYPE_ARRAY = 'array';
    public final const TYPE_NULL = 'null';
}
