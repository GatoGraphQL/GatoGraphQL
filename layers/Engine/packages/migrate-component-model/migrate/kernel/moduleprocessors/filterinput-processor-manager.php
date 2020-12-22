<?php
namespace PoP\ComponentModel;
use PoP\ComponentModel\ItemProcessors\ItemProcessorManagerTrait;

class PoP_FilterInputProcessorManager {

	use ItemProcessorManagerTrait;
}

/**
 * Initialization
 */
global $pop_filterinputprocessor_manager;
$pop_filterinputprocessor_manager = new PoP_FilterInputProcessorManager();
