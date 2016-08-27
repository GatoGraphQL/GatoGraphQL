<?php
/**
 * Echo the Events Manager BuddyPresss component's slug
 * @since 5.0
 */
function em_bp_slug() {
	echo em_bp_get_slug();
}
	/**
	 * Return the Events Manager BuddyPresss component's slug
	 * 
	 * @since 5.0
	 * @uses apply_filters() Filter 'em_bp_get_slug' to change the output
	 * @return str $slug The slug from $bp->events->slug, if it exists
	 */
	function em_bp_get_slug() {
		global $bp;
		// Avoid PHP warnings, in case the value is not set for some reason
		$slug = !empty( $bp->events->slug ) ? $bp->events->slug : BP_EM_SLUG;
		return apply_filters( 'em_bp_get_slug', $slug );
	}