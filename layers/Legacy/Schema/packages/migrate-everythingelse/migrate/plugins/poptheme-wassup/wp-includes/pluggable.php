<?php

// Copied from WP 4.3.1.
if (!function_exists('wp_new_user_notification')) :
    /**
     * Email login credentials to a newly-registered user.
     *
     * A new user registration notification is also sent to admin email.
     *
     * @since 2.0.0
     * @since 4.3.0 The `$plaintext_pass` parameter was changed to `$notify`.
     * @since 4.3.1 The `$plaintext_pass` parameter was deprecated. `$notify` added as a third parameter.
     *
     * @param int    $user_id    User ID.
     * @param null   $deprecated Not used (argument deprecated).
     * @param string $notify     Optional. Type of notification that should happen. Accepts 'admin' or an empty
     *                           string (admin only), or 'both' (admin and user). The empty string value was kept
     *                           for backward-compatibility purposes with the renamed parameter. Default empty.
     */
    function wp_new_user_notification($user_id, $deprecated = null, $notify = '')
    {
    
        // Do nothing, we handle the notification through own hooks
    }
endif;

/**
 * Nonce => Add Media
 */
// Comment Leo 06/08/2017: Added the nonces on the topLevelFeedback whenever the user is logged in,
// so we can, once again, validate the nonce by $uid!
// So then, there's no need to override these 2 functions with custom implementations anymore!

// if ( !function_exists('wp_verify_nonce') ) :
// /**
//  * Changes from GreenDrinks:
//  * In ModuleManager in webplatform, we print the wp_editor wether the user is logged in or not.
//  * So, if originally the user is not logged in and then logs in, of if another user logs in,
//  * since the 'nonce' depends on the user_id, then this validation will fail when clicking on 'Add Media'
//  * So remove the dependency on the user_id
//  *
//  * Verify that correct nonce was used with time limit.
//  *
//  * The user is given an amount of time to use the token, so therefore, since the
//  * UID and $action remain the same, the independent variable is the time.
//  *
//  * @since 2.0.3
//  *
//  * @param string $nonce Nonce that was used in the form to verify
//  * @param string | int $action Should give context to what is taking place and be the same when nonce was created.
//  * @return bool Whether the nonce check passed or failed.
//  */
// function wp_verify_nonce($nonce, $action = -1) {
//     // $user = wp_get_current_user();
//     // $uid = (int) $user->ID;
//     // if ( ! $uid ) {
//     //     *
//     //      * Filter whether the user who generated the nonce is logged out.
//     //      *
//     //      * @since 3.5.0
//     //      *
//     //      * @param int    $uid    ID of the nonce-owning user.
//     //      * @param string $action The nonce action.
//
//     //     $uid = \PoP\Root\App::applyFilters( 'nonce_user_logged_out', $uid, $action );
//     // }
//
//     $i = wp_nonce_tick();
//
//     // Nonce generated 0-12 hours ago
//     if ( substr(wp_hash($i . $action/* . $uid*/, 'nonce'), -12, 10) === $nonce )
//         return 1;
//     // Nonce generated 12-24 hours ago
//     if ( substr(wp_hash(($i - 1) . $action/* . $uid*/, 'nonce'), -12, 10) === $nonce )
//         return 2;
//     // Invalid nonce
//     return false;
// }
// endif;
//
// if ( !function_exists('wp_create_nonce') ) :
//
// /**
//  * Creates a random, one time use token.
//  *
//  * @since 2.0.3
//  *
//  * @param string | int $action Scalar value to add context to the nonce.
//  * @return string The one use form token
//  */
// function wp_create_nonce($action = -1) {
//     // $user = wp_get_current_user();
//     // $uid = (int) $user->ID;
//     // if ( ! $uid ) {
//         /** This filter is documented in wp-includes/pluggable.php */
//         // $uid = \PoP\Root\App::applyFilters( 'nonce_user_logged_out', $uid, $action );
//     // }
//
//     $i = wp_nonce_tick();
//
//     return substr(wp_hash($i . $action/* . $uid*/, 'nonce'), -12, 10);
// }
// endif;


// Comment Leo 06/08/2017: There is a bug in WP regarding the newly introduced function `wp_get_session_token()`:
// When just logging in, in that one session, we have some value of the cookie
// On the next session, that cookie value will be different
// Hence, we cannot generate the nonces just after logging in, as we are currently doing, or they will fail!
// Then, just ignore using that function (as it was before WP 4.0)
if (!function_exists('wp_verify_nonce')) :
    /**
     * Verify that correct nonce was used with time limit.
     *
     * The user is given an amount of time to use the token, so therefore, since the
     * UID and $action remain the same, the independent variable is the time.
     *
     * @since 2.0.3
     *
     * @param  string     $nonce  Nonce that was used in the form to verify
     * @param  string | int $action Should give context to what is taking place and be the same when nonce was created.
     * @return false|int False if the nonce is invalid, 1 if the nonce is valid and generated between
     *                   0-12 hours ago, 2 if the nonce is valid and generated between 12-24 hours ago.
     */
    function wp_verify_nonce($nonce, $action = -1)
    {
        $nonce = (string) $nonce;
        $user = wp_get_current_user();
        $uid = (int) $user->ID;
        if (! $uid) {
            /**
             * Filters whether the user who generated the nonce is logged out.
             *
             * @since 3.5.0
             *
             * @param int    $uid    ID of the nonce-owning user.
             * @param string $action The nonce action.
             */
            $uid = \PoP\Root\App::applyFilters('nonce_user_logged_out', $uid, $action);
        }

        if (empty($nonce)) {
            return false;
        }

        // Hack PoP WP: Ignore getting the value from the cookie
        // $token = wp_get_session_token();
        $token = '';
        $i = wp_nonce_tick();

        // Nonce generated 0-12 hours ago
        $expected = substr(wp_hash($i . '|' . $action . '|' . $uid . '|' . $token, 'nonce'), -12, 10);
        if (hash_equals($expected, $nonce)) {
            return 1;
        }

        // Nonce generated 12-24 hours ago
        $expected = substr(wp_hash(($i - 1) . '|' . $action . '|' . $uid . '|' . $token, 'nonce'), -12, 10);
        if (hash_equals($expected, $nonce)) {
            return 2;
        }

        /**
         * Fires when nonce verification fails.
         *
         * @since 4.4.0
         *
         * @param string     $nonce  The invalid nonce.
         * @param string | int $action The nonce action.
         * @param WP_User    $user   The current user object.
         * @param string     $token  The user's session token.
         */
        \PoP\Root\App::doAction('wp_verify_nonce_failed', $nonce, $action, $user, $token);

        // Invalid nonce
        return false;
    }
endif;

if (!function_exists('wp_create_nonce')) :
    /**
     * Creates a cryptographic token tied to a specific action, user, user session,
     * and window of time.
     *
     * @since 2.0.3
     * @since 4.0.0 Session tokens were integrated with nonce creation
     *
     * @param  string | int $action Scalar value to add context to the nonce.
     * @return string The token.
     */
    function wp_create_nonce($action = -1)
    {
        $user = wp_get_current_user();
        $uid = (int) $user->ID;
        if (! $uid) {
            /**
        * This filter is documented in wp-includes/pluggable.php
*/
            $uid = \PoP\Root\App::applyFilters('nonce_user_logged_out', $uid, $action);
        }

        // Hack PoP WP: Ignore getting the value from the cookie
        // $token = wp_get_session_token();
        $token = '';
        $i = wp_nonce_tick();

        return substr(wp_hash($i . '|' . $action . '|' . $uid . '|' . $token, 'nonce'), -12, 10);
    }
endif;
