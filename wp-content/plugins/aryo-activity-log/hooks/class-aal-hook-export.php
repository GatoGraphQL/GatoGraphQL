<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AAL_Hook_Export extends AAL_Hook_Base {

	public function hooks_export_wp( $args ) {
		aal_insert_log(
			array(
				'action' => 'downloaded',
				'object_type' => 'Export',
				'object_id' => 0,
				'object_name' => isset( $args['content'] ) ? $args['content'] : 'all',
			)
		);
	}

	public function __construct() {
		add_action( 'export_wp', array( &$this, 'hooks_export_wp' ) );
		
		parent::__construct();
	}
	
}