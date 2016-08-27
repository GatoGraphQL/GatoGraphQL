<?php
	if ( ! class_exists( 'Freemius_InvalidArgumentException' ) ) {
		exit;
	}

    class Freemius_ArgumentNotExistException extends Freemius_InvalidArgumentException { }