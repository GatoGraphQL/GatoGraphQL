<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AAL_Hook_Menu extends AAL_Hook_Base {

	public function hooks_menu_created_or_updated( $nav_menu_selected_id ) {
		if ( $menu_object = wp_get_nav_menu_object( $nav_menu_selected_id ) ) {
			if ( 'wp_create_nav_menu' === current_filter() )
				$action = 'created';
			else
				$action = 'updated';
			
			aal_insert_log(
				array(
					'action'      => $action,
					'object_type' => 'Menu',
					'object_name' => $menu_object->name,
				)
			);
		}
	}

	public function hooks_menu_deleted( $term, $tt_id, $deleted_term ) {
		aal_insert_log(
			array(
				'action'      => 'deleted',
				'object_type' => 'Menu',
				'object_name' => $deleted_term->name,
			)
		);
	}
	
	public function __construct() {
		add_action( 'wp_update_nav_menu', array( &$this, 'hooks_menu_created_or_updated' ) );
		add_action( 'wp_create_nav_menu', array( &$this, 'hooks_menu_created_or_updated' ) );
		add_action( 'delete_nav_menu', array( &$this, 'hooks_menu_deleted' ), 10, 3 );
		parent::__construct();
	}

}