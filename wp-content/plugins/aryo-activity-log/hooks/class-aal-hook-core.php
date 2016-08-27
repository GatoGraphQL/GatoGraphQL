<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AAL_Hook_Core extends AAL_Hook_Base {

	public function core_updated_successfully( $wp_version ) {
		global $pagenow;

		// Auto updated
		if ( 'update-core.php' !== $pagenow )
			$object_name = 'WordPress Auto Updated';
		else
			$object_name = 'WordPress Updated';

		aal_insert_log(
			array(
				'action'      => 'updated',
				'object_type' => 'Core',
				'object_id'   => 0,
				'object_name' => $object_name,
			)
		);
	}

	public function __construct() {
		add_action( '_core_updated_successfully', array( &$this, 'core_updated_successfully' ) );
		
		parent::__construct();
	}
	
}