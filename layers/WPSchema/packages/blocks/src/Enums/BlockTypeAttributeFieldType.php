<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\Enums;

/**
 * Possible values of a block attribute's "type" property,
 * as defined by the JSON Schema spec used by `block.json`.
 */
class BlockTypeAttributeFieldType
{
    public final const STRING = 'string';
    public final const INTEGER = 'integer';
    public final const NUMBER = 'number';
    public final const BOOLEAN = 'boolean';
    public final const ARRAY = 'array';
    public final const OBJECT = 'object';
    public final const NULL = 'null';
}
