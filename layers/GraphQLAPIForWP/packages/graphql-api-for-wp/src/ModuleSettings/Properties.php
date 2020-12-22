<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ModuleSettings;

class Properties
{
    public const NAME = 'name';
    public const INPUT = 'input';
    public const TITLE = 'title';
    public const DESCRIPTION = 'description';
    public const POSSIBLE_VALUES = 'possibleValues';
    // Used for Select inputs
    public const IS_MULTIPLE = 'isMultiple';
    // Used for Integers
    public const MIN_NUMBER = 'minNumber';
    public const TYPE = 'type';
    public const TYPE_STRING = 'string';
    public const TYPE_BOOL = 'bool';
    public const TYPE_INT = 'int';
    public const TYPE_ARRAY = 'array';
}
