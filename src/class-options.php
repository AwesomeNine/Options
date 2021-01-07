<?php
/**
 * Option manger class
 *
 * @since   1.0.0
 * @package Awesome9\Options
 * @author  Awesome9 <me@awesome9.co>
 */

namespace Awesome9\Options;

/**
 * Options class
 */
class Options {

	/**
	 * Option holder.
	 *
	 * @var array
	 */
	private $options = array();

	/**
	 * Magic function override.
	 *
	 * @param string $name      Name of function.
	 * @param array  $arguments Arguments.
	 */
	public function __call( $name, $arguments ) {
		if ( array_key_exists( $name, $this->options ) ) {
			$option = $this->options[ $name ];
			return empty( $arguments ) ? $option : $option->get( ...$arguments );
		}

		return $this->$name( ...$arguments );
	}

	/**
	 * Retrieve main instance.
	 *
	 * Ensure only one instance is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 *
	 * @return Options
	 */
	public static function get() {
		static $instance;

		if ( is_null( $instance ) && ! ( $instance instanceof Options ) ) {
			$instance = new Options();
		}

		return $instance;
	}

	/**
	 * Bind all hooks.
	 *
	 * @since 1.0.0
	 *
	 * @return Options
	 */
	public function hooks() {
		add_action( 'init', array( $this, 'is_sitepress_active' ) );

		return $this;
	}

	/**
	 * If sitepress is active reset all option.
	 */
	public function is_sitepress_active() {
		if ( ! isset( $GLOBALS['sitepress'] ) ) {
			return;
		}

		foreach ( $this->options as $option ) {
			$option->reset();
		}
	}

	/**
	 * Register options.
	 *
	 * @since  1.0.0
	 *
	 * @param string $name       Unique identifier.
	 * @param string $option_key The option key to save into wp_options.
	 * @param array  $defaults   The defaults values.
	 *
	 * @return Options
	 */
	public function register( $name, $option_key, $defaults = array() ) {
		// Early Bail!!
		if ( empty( $name ) || empty( $option_key ) || isset( $this->options[ $name ] ) ) {
			return $this;
		}

		$this->options[ $name ] = new Option( $option_key, $defaults );

		return $this;
	}

	/**
	 * Installation of default options routine.
	 *
	 * @return Options
	 */
	public function install() {
		foreach ( $this->options as $option ) {
			$option->install();
		}

		return $this;
	}
}
