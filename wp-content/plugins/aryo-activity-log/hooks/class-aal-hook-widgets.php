<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AAL_Hook_Widgets extends AAL_Hook_Base {

	public function hooks_widget_update_callback( $instance, $new_instance, $old_instance, WP_Widget $widget ) {
		$aal_args = array(
			'action'         => 'updated',
			'object_type'    => 'Widget',
			'object_subtype' => 'sidebar_unknown',
			'object_id'      => 0,
			'object_name'    => $widget->id_base,
		);

		if ( ! empty( $_REQUEST['sidebar'] ) )
			$aal_args['object_subtype'] = strtolower( $_REQUEST['sidebar'] );

		/** @todo: find any way to widget deleted detected */
		/*if ( isset( $_REQUEST['delete_widget'] ) && '1' === $_REQUEST['delete_widget'] ) {
			$aal_args['action'] = 'deleted';
		}*/

		aal_insert_log( $aal_args );

		// We are need return the instance, for complete the filter.
		return $instance;
	}

	public function hooks_widget_delete() {
		// A reference: http://grinninggecko.com/hooking-into-widget-delete-action-in-wordpress/
		if ( 'post' == strtolower( $_SERVER['REQUEST_METHOD'] ) && ! empty( $_REQUEST['widget-id'] ) ) {
			if ( isset( $_REQUEST['delete_widget'] ) && 1 === (int) $_REQUEST['delete_widget'] ) {
				aal_insert_log( array(
					'action'         => 'deleted',
					'object_type'    => 'Widget',
					'object_subtype' => strtolower( $_REQUEST['sidebar'] ),
					'object_id'      => 0,
					'object_name'    => $_REQUEST['id_base'],
				) );
			}
		}
	}
	
	public function __construct() {
		add_filter( 'widget_update_callback', array( &$this, 'hooks_widget_update_callback' ), 9999, 4 );
		add_filter( 'sidebar_admin_setup', array( &$this, 'hooks_widget_delete' ) ); // Widget delete.
		
		parent::__construct();
	}

}