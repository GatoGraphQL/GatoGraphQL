<?php
	/**
	 * @package     Freemius
	 * @copyright   Copyright (c) 2015, Freemius, Inc.
	 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
	 * @since       1.0.5
	 */

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}

	class FS_Plugin_Plan extends FS_Entity {

		#region Properties

		/**
		 * @var string
		 */
		public $title;
		/**
		 * @var string
		 */
		public $name;
		/**
		 * @var int Trial days.
		 */
		public $trial_period;
		/**
		 * @var string If true, require payment for trial.
		 */
		public $is_require_subscription;

		#endregion Properties

		/**
		 * @param object|bool $plan
		 */
		function __construct( $plan = false ) {
			parent::__construct( $plan );

			if ( is_object( $plan ) ) {
				$this->name = strtolower( $plan->name );
			}
		}

		static function get_type() {
			return 'plan';
		}

		/**
		 * @author Vova Feldman (@svovaf)
		 * @since  1.0.9
		 *
		 * @return bool
		 */
		function is_free() {
			return ( 'free' === $this->name );
		}

		/**
		 * @author Vova Feldman (@svovaf)
		 * @since  1.0.9
		 *
		 * @return bool
		 */
		function has_trial() {
			return ! $this->is_free() &&
			       is_numeric( $this->trial_period ) && ( $this->trial_period > 0 );
		}
	}