<?php
	if ( ! class_exists( 'Freemius_Exception' ) ) {
		exit;
	}

    class Freemius_InvalidArgumentException extends Freemius_Exception { }