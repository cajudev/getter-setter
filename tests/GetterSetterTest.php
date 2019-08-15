<?php

namespace App;

use PHPUnit\Framework\TestCase;

use Cajudev\GetterSetter;
use Cajudev\Exception\GetterSetterException;
use Cajudev\Exception\GetterSetterAccessException;

class GetterSetterTest extends TestCase {
	private $class;

	public function setUp() {
		$this->object = new class() {
			use \Cajudev\GetterSetterAccessor;
		
			/** @GetterSetter(bool) */
			private $bool;
		};
	}

	public function test_should_throws_exception_when_get_invalid_property() {
		self::expectException(GetterSetterAccessException::class);
		$this->object->boool;
	}

	public function test_should_throws_exception_when_set_invalid_property() {
		self::expectException(GetterSetterAccessException::class);
		$this->object->boool = true;
	}

	public function test_should_throws_exception_when_get_not_registered_property() {
		self::expectException(GetterSetterException::class);
		$this->object->bool;
	}

	public function test_should_throws_exception_when_set_not_registered_property() {
		self::expectException(GetterSetterException::class);
		$this->object->bool = true;
	}

	public function test_should_throws_exception_when_get_registered_property_without_get_handler() {
		self::expectException(GetterSetterException::class);
		GetterSetter::register('bool', []);
		$this->object->bool;
	}

	public function test_should_throws_exception_when_set_registered_property_without_set_handler() {
		self::expectException(GetterSetterException::class);
		GetterSetter::register('bool', []);
		$this->object->bool = true;
	}

	public function test_should_throws_exception_when_registered_get_handler_is_not_callable() {
		self::expectException(GetterSetterException::class);
		GetterSetter::register('bool', [
			'get' => 'NOT CALLABLE'
		]);
		$this->object->bool;
	}

	public function test_should_throws_exception_when_registered_set_handler_is_not_callable() {
		self::expectException(GetterSetterException::class);
		GetterSetter::register('bool', [
			'set' => 'NOT CALLABLE'
		]);
		$this->object->bool = true;
	}

	public function test_should_validate_property_when_register_correctly() {
		GetterSetter::register('bool', [
			'get' => function($value) {
				return $value;
			},
			'set' => function($value) {
				return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
			}
		]);
		$this->object->bool = 'yes';
		self::assertTrue($this->object->bool);
	}

	public function test_should_ignore_case_of_keys() {
		GetterSetter::register('bool', [
			'GET' => function($value) {
				return $value;
			},
			'Set' => function($value) {
				return filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
			}
		]);
		$this->object->bool = 'false';
		self::assertFalse($this->object->bool);
	}
}