<?php
namespace PoP\Engine;

abstract class FieldProcessor_FunctionalHookBase extends FieldProcessor_HookBase
{
    public function getFieldType()
    {
        return GD_DATALOAD_FIELDPROCESSOR_FIELDTYPE_FUNCTIONAL;
    }
}
