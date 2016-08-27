<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Nonces
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_NONCE_EDITURL', 'gd-nonce-editurl');

function gd_get_nonce_url($noncestring, $url, $id) {

	return wp_nonce_url($url, gd_get_nonce_id($noncestring, $id));
}

function gd_create_nonce($noncestring, $id) {

	return wp_create_nonce(gd_get_nonce_id($noncestring, $id));
}

function gd_verify_nonce($nonce, $noncestring, $id) {

	return wp_verify_nonce( $nonce, gd_get_nonce_id($noncestring, $id) );
}

function gd_get_nonce_id($noncestring, $id) {

	return $noncestring . '-' . $id;
}