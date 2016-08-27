<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class AAL_Hook_Comments extends AAL_Hook_Base {
	
	protected function _add_comment_log( $id, $action, $comment = null ) {
		if ( is_null( $comment ) )
			$comment = get_comment( $id );
		
		aal_insert_log( array(
			'action'         => $action,
			'object_type'    => 'Comments',
			'object_subtype' => get_post_type( $comment->comment_post_ID ),
			'object_name'    => get_the_title( $comment->comment_post_ID ),
			'object_id'      => $id,
		) );
	}
	
	public function handle_comment_log( $comment_ID, $comment = null ) {
		if ( is_null( $comment ) )
			$comment = get_comment( $comment_ID );
		
		$action = 'created';
		switch ( current_filter() ) {
			case 'wp_insert_comment' :
				$action = 1 === (int) $comment->comment_approved ? 'approved' : 'pending';
				break;
			
			case 'edit_comment' :
				$action = 'updated';
				break;

			case 'delete_comment' :
				$action = 'deleted';
				break;
			
			case 'trash_comment' :
				$action = 'trashed';
				break;
			
			case 'untrash_comment' :
				$action = 'untrashed';
				break;
			
			case 'spam_comment' :
				$action = 'spammed';
				break;
			
			case 'unspam_comment' :
				$action = 'unspammed';
				break;
		}
		
		$this->_add_comment_log( $comment_ID, $action, $comment );
	}

	public function hooks_transition_comment_status( $new_status, $old_status, $comment ) {
		$this->_add_comment_log( $comment->comment_ID, $new_status, $comment );
	}

	public function __construct() {
		add_action( 'wp_insert_comment', array( &$this, 'handle_comment_log' ), 10, 2 );
		add_action( 'edit_comment', array( &$this, 'handle_comment_log' ) );
		add_action( 'trash_comment', array( &$this, 'handle_comment_log' ) );
		add_action( 'untrash_comment', array( &$this, 'handle_comment_log' ) );
		add_action( 'spam_comment', array( &$this, 'handle_comment_log' ) );
		add_action( 'unspam_comment', array( &$this, 'handle_comment_log' ) );
		add_action( 'delete_comment', array( &$this, 'handle_comment_log' ) );
		add_action( 'transition_comment_status', array( &$this, 'hooks_transition_comment_status' ), 10, 3 );
		
		parent::__construct();
	}
	
}