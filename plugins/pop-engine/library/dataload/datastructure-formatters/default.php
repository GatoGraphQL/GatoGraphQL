<?php
namespace PoP\Engine\Impl;

define('GD_DATALOAD_DATASTRUCTURE_DEFAULT', 'default');

class DataStructureFormatter_Default extends \PoP\Engine\DataStructureFormatterBase
{
    public function getName()
    {
        return GD_DATALOAD_DATASTRUCTURE_DEFAULT;
    }
}
    
/**
 * Initialize
 */
$gd_dataload_formatter_default = new DataStructureFormatter_Default();

// Set as the default one
$datastructureformat_manager = \PoP\Engine\DataStructureFormat_Manager_Factory::getInstance();
$datastructureformat_manager->setDefault($gd_dataload_formatter_default);
