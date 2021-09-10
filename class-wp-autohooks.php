<?php
/**
 * WP Autohooks
 *
 * Automatically add actions, filters and shortcodes by declaring a
 * the static variable $wp_autohooks inside your methods.
 *
 * Solves the problem of having to declare actions, filters and
 * shortcodes separate of the callback method.
 *
 * @author Mike Lopez
 *
 * @package WP_Autohooks
 */

/**
 * WP_Autohooks trait
 */
trait WP_Autohooks {
	/**
	 * Do the magic...
	 *
	 * Calls this method after creating your object.
	 */
	public function auto_hooks() {
		$class = new ReflectionClass( $this );
		// go through each public method in this class.
		foreach ( $class->getMethods( ReflectionMethod::IS_PUBLIC ) as $method ) {
			// get static variables for the method.
			$statics = $method->getStaticVariables();
			// look for the $wp_autohooks static variable.
			if ( isset( $statics['wp_autohooks'] ) ) {
				// go through each value of $wp_autohooks.
				foreach ( (array) $statics['wp_autohooks'] as $hook ) {
					// find a valid action, filter or shortcode string.
					if ( preg_match_all( '/^\s*(action|filter|shortcode)\s+([^\s\']+)(?:\s+(\d+))?(?:\s+(\d+))?\s*$/', $hook, $matches, PREG_SET_ORDER ) ) {
						foreach ( $matches as $match ) {
							unset( $match[0] ); // no need for the first match.
							/**
							 * Hook function to call.
							 * Can be any of add_action, add_filter or add_shortcode.
							 *
							 * @var string
							 */
							$function = 'add_' . array_shift( $match );

							/**
							 * The hook or shortcode name.
							 *
							 * @var string
							 */
							$hook = array_shift( $match );

							/**
							 * Priority and number of arguments accepted
							 *
							 * @var int[]
							 */
							$args = array_diff( $match, array( '' ) );

							// register our action, filter or shortcode.
							$function( $hook, array( $this, $method->name ), ...$args );
						}
					}
				}
			}
		}
	}
}
