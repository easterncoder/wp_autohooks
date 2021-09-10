# WP Autohooks
Automatically add actions, filters and shortcodes by declaring the static variable $wp_autohooks inside your class methods.

Solves the problem of having to declare actions, filters and shortcodes separate of the callback method.

## Steps to use:

1. Include the `class-wp-autohooks.php` file
2. Use the WP_Autohooks trait in your class (ie. use `WP_Autohooks;`)
3. Add a static variable called `$wp_autohooks` inside each public method that is to be used as an action, filter or shortcode. See format below.
4. Create your object
5. Call the `auto_hooks()` method.

## $wp_autohooks static variable format definition

As string:
```php
static $wp_autohooks = 'action|filter|shortcode, {action_name|filter_name|shortcode_name}, {optional_priority}, {optional_argument_count}'
```

As array:
```php
$static $wp_autohooks = array( 'action|filter|shortcode', '{action_name|filter_name|shortcode_name}', {optional_priority}, {optional_argument_count} )
```

## Specifying single hooks

As string:
```php
static $wp_autohooks = 'filter, the_content';
```

As array:
```php
static $wp_autohooks = array( 'action', 'plugins_loaded' );
```

## Specifying multiple hooks

To specify multiple hooks, create an array of definitions.

As string:
```php
static $wp_autohooks = array( 'action init', 'action shutdown' );
```

As array:
```php
static $wp_autohooks = array( array( 'shortcode', 'first_name' ), array( 'shortcode', 'firstname' ) );
```

## Specifying priority and argument count

As string:
```php
// wp_insert_post_data filter with a priority of 5 and 3 accepted arguments.
static $wp_autohooks = 'filter, wp_insert_post_data, 25, 3';
```

As array:
```php
// wp_insert_post_data filter with a priority of 5 and 3 accepted arguments.
static $wp_autohooks = array( 'filter', 'wp_insert_post_data', 25, 3 );
```

## Sample code

```php
// 1. require the WP_Autohooks file.
require_once 'class-wp-autohooks.php';

/**
 * Some Class with WP hooks and shortcodes.
 */
class Some_Class {
	// 2. load the WP_Autohooks trait.
	use WP_Autohooks;
	
	/**
	 * 'init' action
	 */
	public function my_init_action() {
		// 3. $wp_autohooks definition.
		static $wp_autohooks = array( 'action init' );

		// do init stuff here.
	}

	/**
	 * First name shortcode
	 *
	 * @return string
	 */
	public function firstname() {
		// 3. $wp_autohooks definition.
		static $wp_autohooks = array(
			array( 'shortcode', 'firstname' ),
			array( 'shortcode', 'first_name' ),
		);

		// get and return first name here.
		return 'something here';
	}

	/**
	 * 'the_content' filter
	 *
	 * @param  string $arg1 Content
	 * @return string
	 */
	public function content_filter( $arg1 ) {
		// 3. $wp_autohooks definition.
		static $wp_autohooks = array( 'filter', 'the_content', 25 );

		// do filter stuff here.
		return $arg1 . 'appended text';
	}
}

// 4. Create your object.
$myobj = new Some_Class();

// 5. Call the auto_hooks() method.
$myobj->auto_hooks();

```
