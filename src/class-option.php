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
 * Handles getting/setting of values to an option.
 */
class Option {

	/**
	 * Current option key
	 *
	 * @var string
	 */
	protected $key = '';

	/**
	 * Options array
	 *
	 * @var array
	 */
	protected $options = null;

	/**
	 * Raw options array
	 *
	 * @var array
	 */
	protected $raw_options = null;

	/**
	 * Defaults array
	 *
	 * @var array
	 */
	protected $defaults = array();

	/**
	 * Initiate option object
	 *
	 * @param string $option_key The option key to save into wp_options.
	 * @param array  $defaults   The defaults values.
	 */
	public function __construct( $option_key, $defaults = array() ) {
		$this->key      = $option_key;
		$this->defaults = $defaults;
	}

	/**
	 * Install default option
	 */
	public function install() {
		add_option( $this->key, $this->defaults );
	}

	/**
	 * Reset option
	 */
	public function reset() {
		$this->options = null;
		$this->get_options();
	}

	/**
	 * Delete the option from the db
	 *
	 * @return mixed Delete success or failure
	 */
	public function delete_option() {
		$deleted       = $this->key ? delete_option( $this->key ) : true;
		$this->options = $deleted ? array() : $this->options;

		return $this->options;
	}

	/**
	 * Removes an option from an option array
	 *
	 * @param string $field_id Option array field key.
	 * @param bool   $resave Whether or not to resave.
	 *
	 * @return array Modified options
	 */
	public function remove( $field_id, $resave = false ) {
		$this->get_options();

		if ( isset( $this->options[ $field_id ] ) ) {
			unset( $this->options[ $field_id ] );
			unset( $this->raw_options[ $field_id ] );
		}

		if ( $resave ) {
			$this->set();
		}

		return $this->options;
	}

	/**
	 * Retrieves an option from an option array
	 *
	 * @param string $field_id Option array field key.
	 * @param mixed  $default  Fallback value for the option.
	 *
	 * @return array Requested field or default
	 */
	public function get( $field_id, $default = false ) {
		$opts = $this->get_options();

		if ( 'all' === $field_id ) {
			return $opts;
		}

		$ids = explode( '.', $field_id );
		foreach ( $ids as $id ) {
			if ( is_null( $opts ) ) {
				break;
			}
			$opts = isset( $opts[ $id ] ) ? $opts[ $id ] : null;
		}

		if ( is_null( $opts ) ) {
			return $default;
		}

		return $opts;
	}

	/**
	 * Updates Option data
	 *
	 * @param string $field_id Option array field key.
	 * @param mixed  $value    Value to update data with.
	 * @param bool   $resave   Whether to re-save the data.
	 * @param bool   $single   Whether data should not be an array.
	 *
	 * @return bool Return status of update.
	 */
	public function update( $field_id, $value = '', $resave = false, $single = true ) {
		$this->get_options();

		// Early Bail!!
		if ( empty( $field_id ) ) {
			return false;
		}

		if ( ! $single ) {
			// If multiple, add to array.
			$this->raw_options[ $field_id ][] = $value;
			$this->options[ $field_id ][]     = $this->normalize( $value );
		} else {
			$this->raw_options[ $field_id ] = $value;
			$this->options[ $field_id ]     = $this->normalize( $value );
		}

		if ( $resave ) {
			return $this->set();
		}

		return true;
	}

	/**
	 * Saves the option array
	 *
	 * @return bool Success/Failure
	 */
	public function set() {
		$this->raw_options = wp_unslash( $this->raw_options ); // get rid of those evil magic quotes.
		return update_option( $this->key, $this->raw_options );
	}

	/**
	 * Retrieve option value based on name of option.
	 *
	 * @return mixed Value set for the option.
	 */
	public function get_options() {
		if ( ! is_null( $this->options ) ) {
			return $this->options;
		}

		$this->raw_options = get_option( $this->key, array() );
		$this->options     = $this->normalize_it( $this->raw_options );

		return $this->options;
	}

	/**
	 * Normalize option data
	 *
	 * @param mixed $options Array to normalize.
	 *
	 * @return mixed
	 */
	private function normalize_it( $options ) {
		foreach ( (array) $options as $key => $value ) {
			$options[ $key ] = is_array( $value ) ? $this->normalize_it( $value ) : $this->normalize( $value );
		}

		return $options;
	}

	/**
	 * Normalize option value.
	 *
	 * @param mixed $value Value to normalize.
	 *
	 * @return mixed
	 */
	private function normalize( $value ) {
		if ( 'true' === $value || 'on' === $value ) {
			$value = true;
		} elseif ( 'false' === $value || 'off' === $value ) {
			$value = false;
		} elseif ( '0' === $value || '1' === $value ) {
			$value = intval( $value );
		}

		return $value;
	}
}
