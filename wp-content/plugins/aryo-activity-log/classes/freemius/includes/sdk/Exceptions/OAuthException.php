<?php
	if ( ! class_exists( 'Freemius_Exception' ) ) {
		exit;
	}

    class Freemius_OAuthException extends Freemius_Exception
    {
        public function __construct($pResult)
        {
            parent::__construct($pResult);
        }
    }