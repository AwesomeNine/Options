<?php
/**
 * Class TestOptions
 *
 * @since   1.0.0
 * @package Awesome9\Options
 * @author  Awesome9 <me@awesome9.co>
 */

namespace Awesome9\Options\Test;

use WP_UnitTestCase;
use Awesome9\Options\Options;

/**
 * Options test case.
 */
class TestOptions extends WP_UnitTestCase {

	public function test_register_with_defaults() {
		$manager = Options::get()->hooks()
			->register( 'awesome9', '_awesome9_plugin_settings', [ 'name' => 'shakeeb' ] );

		$manager->install();

		$option = get_option( '_awesome9_plugin_settings' );
		$this->assertArrayEquals( $option, [ 'name' => 'shakeeb' ] );
	}

	public function test_get_value() {
		$manager = Options::get()->hooks()
			->register(
				'awesome8',
				'_awesome8_plugin_settings',
				[
					'name'  => 'shakeeb',
					'truth' => 'on',
					'falsy' => 'off',
				]
			);

		$manager->install();

		$this->assertEquals( $manager->awesome8( 'name' ), 'shakeeb' );
		$this->assertTrue( $manager->awesome8( 'truth' ) );
		$this->assertFalse( $manager->awesome8( 'falsy' ) );

		$manager->awesome8()->update( 'truth', 'off', true );
		$this->assertFalse( $manager->awesome8( 'truth' ) );

		$option = get_option( '_awesome8_plugin_settings' );
		$this->assertArrayEquals(
			$option,
			[
				'name'  => 'shakeeb',
				'truth' => 'off',
				'falsy' => 'off',
			]
		);
	}

	public function assertArrayEquals( $array1, $array2 ) {
		$this->assertEquals( json_encode( $array1 ), json_encode( $array2 ) );
	}

	public function getPrivate( $obj, $attribute ) {
		$getter = function() use ( $attribute ) {
			return $this->$attribute;
		};
		$get = \Closure::bind( $getter, $obj, get_class( $obj ) );
		return $get();
	}
}
