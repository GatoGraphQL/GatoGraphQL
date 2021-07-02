<?php

define('GD_NONCE_EDITURL', 'gd-nonce-editurl');

function gdGetNonceUrl($noncestring, $url, $id)
{
    return wp_nonce_url($url, gdGetNonceId($noncestring, $id), POP_INPUTNAME_NONCE);
}

function gdCreateNonce($noncestring, $id)
{
    return wp_create_nonce(gdGetNonceId($noncestring, $id));
}

function gdVerifyNonce($nonce, $noncestring, $id)
{
    return wp_verify_nonce($nonce, gdGetNonceId($noncestring, $id));
}

function gdGetNonceId($noncestring, $id)
{
    return $noncestring . '-' . $id;
}
