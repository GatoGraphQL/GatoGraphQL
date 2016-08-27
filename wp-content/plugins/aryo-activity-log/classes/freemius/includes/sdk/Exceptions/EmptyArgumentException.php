<?php
	if ( ! class_exists( 'Freemius_InvalidArgumentException' ) ) {
		exit;
	}

    class Freemius_EmptyArgumentException extends Freemius_InvalidArgumentException { }