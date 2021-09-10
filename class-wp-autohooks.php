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
		$class = new \ReflectionClass( $this );
		// go through each public method in this class.
		foreach ( $class->getMethods( \ReflectionMethod::IS_PUBLIC ) as $method ) {
			// get static variables for the method.
			$statics = $method->getStaticVariables();
			// look for the $wp_autohooks static variable.
			if ( isset( $statics['wp_autohooks'] ) ) {
				// go through each value of $wp_autohooks.
				$statics = (array) $statics['wp_autohooks'];
				if ( in_array( $statics[0], array( 'action', 'filter', 'shortcode' ), true ) ) {
					$statics = array( $statics );
				}
				foreach ( $statics as $hooks ) {
					if ( is_array( $hooks ) ) { // hooks are specified as arrays.
						$hooks_is_string = false;
						$hooks           = array( array_map( 'trim', $hooks ) );
					} elseif (
						preg_match_all(
							'/^\s*(action|filter|shortcode)\s+([^\s\']+)(?:\s+(\d+))?(?:\s+(\d+))?\s*$/',
							str_replace( ',', ' ', $hooks ),
							$matches,
							PREG_SET_ORDER
						)
					) { // hooks are specified as strings so we parse it.
						$hooks_is_string = true;
						$hooks           = $matches;
					}

					foreach ( $hooks as $hook ) {
						if ( $hooks_is_string ) {
							unset( $hook[0] ); // discard first array entry for string hooks.
						}
						/**
						 * Hook function to call.
						 * Can be any of add_action, add_filter or add_shortcode.
						 *
						 * @var string
						 */
						$function = 'add_' . array_shift( $hook );

						/**
						 * The hook or shortcode name.
						 *
						 * @var string
						 */
						$hook_name = array_shift( $hook );

						/**
						 * Priority and number of arguments accepted
						 *
						 * @var int[]
						 */
						$args = array_diff( $hook, array( '' ) );

						// register our action, filter or shortcode.
						$function( $hook_name, array( $this, $method->name ), ...$args );
					}
				}
			}
		}
	}
}
