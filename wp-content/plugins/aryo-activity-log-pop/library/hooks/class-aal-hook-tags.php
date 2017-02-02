<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



class AAL_PoP_Hook_Tags extends AAL_Hook_Base {

	public function __construct() {

		// Subscribed to/Unsubscribed from
		add_action('gd_subscribetotag', array($this, 'subscribedto_tag'));
		add_action('gd_unsubscribefromtag', array($this, 'unsubscribedfrom_tag'));

		parent::__construct();
	}

	public function subscribedto_tag($subscribedto_tag_id) {

		$this->subscribedtounsubscribedfrom_tag($subscribedto_tag_id, AAL_POP_ACTION_USER_SUBSCRIBEDTOTAG);
	}

	public function unsubscribedfrom_tag($unsubscribedfrom_tag_id) {

		$this->subscribedtounsubscribedfrom_tag($unsubscribedfrom_tag_id, AAL_POP_ACTION_USER_UNSUBSCRIBEDFROMTAG);
	}

	public function subscribedtounsubscribedfrom_tag($tag_id, $action) {

		$tag = get_tag($tag_id);
		aal_insert_log( array(
			'action'      => $action,
			'object_type' => 'Taxonomy',
			'object_subtype' => 'Tag',
			'user_id'     => get_current_user_id(),
			'object_id'   => $tag_id,
			'object_name' => PoP_TagUtils::get_tag_symbol().$tag->name,
		) );
	}
}