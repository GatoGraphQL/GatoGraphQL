<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Contextual help texts
 *
 * Class AAL_Help
 */
class AAL_Help {
    public function __construct() {
        add_action( 'in_admin_header', array( $this, 'contextual_help' ) );
    }

    public function contextual_help() {
        $screen = get_current_screen();

        switch ( $screen->id ) {
            case 'activity-log_page_activity-log-settings':
                $screen->add_help_tab( array(
                    'title' => __( 'Overview', 'aryo-activity-log' ),
                    'id' => 'aal-overview',
                    'content' => '
                    <h3>' . __( 'Notifications', 'aryo-activity-log' ) . '</h3>
                    <p>' . __( 'This screen lets you control what will happen once a user on your site does something you define. For instance, let us assume that you have created a user on your site
                    for your content editor. Now, let\'s say that every time that user updates a post, you want to know about it. You can easily do it from this page.', 'aryo-activity-log' ) . '</p>',
                ) );
                break;
        }
    }
}