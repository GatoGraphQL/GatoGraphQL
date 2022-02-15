<?php

// Validate that the private key has been defined, or fail otherwise
if (!defined('POP_CAPTCHA_PRIVATEKEYS_CAPTCHA') || !POP_CAPTCHA_PRIVATEKEYS_CAPTCHA) {
    throw new \PoP\Root\Exception\GenericException("Private key POP_CAPTCHA_PRIVATEKEYS_CAPTCHA has not been defined", 1);
}
if (!defined('POP_CAPTCHA_PRIVATEKEYS_IV') || !POP_CAPTCHA_PRIVATEKEYS_IV) {
    throw new \PoP\Root\Exception\GenericException("Private key POP_CAPTCHA_PRIVATEKEYS_IV has not been defined", 1);
}
