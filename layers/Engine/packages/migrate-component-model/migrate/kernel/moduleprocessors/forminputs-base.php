<?php
namespace PoP\ComponentModel;
use PoP\ComponentModel\ModuleProcessors\AbstractQueryDataModuleProcessor;
abstract class AbstractFormInputs extends AbstractQueryDataModuleProcessor implements FormComponent
{
    use FormInputsTrait;
}
