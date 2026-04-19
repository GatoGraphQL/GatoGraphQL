<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\Enums;

/**
 * Possible values of a block attribute's "type" property,
 * as defined by the JSON Schema spec used by `block.json`.
 */
class BlockTypeAttributeFieldType
{
    public final const STRING = 'STRING';
    public final const INTEGER = 'INTEGER';
    public final const NUMBER = 'NUMBER';
    public final const BOOLEAN = 'BOOLEAN';
    public final const ARRAY = 'ARRAY';
    public final const OBJECT = 'OBJECT';
    public final const NULL = 'NULL';
}
