# WP Autohooks
Automatically add actions, filters and shortcodes by declaring the static variable $wp_autohooks inside your class methods.

Solves the problem of having to declare actions, filters and shortcodes separate of the callback method.

Usage best explain with the examples below:

```php

// require the WP_Autohooks file.
require_once 'class-wp-autohooks.php';

/**
 * Some Class with WP hooks and shortcodes.
 */
class Some_Class {
	// load the WP_Autohooks trait.
	use WP_Autohooks;
	
	public function my_init_action() {
		static $wp_autohooks = array( 'action init' );
	}
	
	public function some_filter_with_priority() {
		static $wp_autohooks = array( 'filter somehook 99' );
	}
	
	public function some_action_with_priority_and_arguments( $arg1, $arg2 ) {
		static $wp_autohooks = array( 'action somehook 15 2' );
	}
	
	public function my_shortcode() {
		static $wp_autohooks = array( 'shortcode my_shortcode_name ');
	}
	
	public function function_with_multiple_actions() {
		static $wp_autohooks = array( 'action init', 'action shutdown');
	}
}

// create your object
$myobj = new Some_Class();

// and call the auto_hooks() method.
$myobj->auto_hooks();

```
