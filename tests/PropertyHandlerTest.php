<?php

namespace App;

use Cajudev\PropertyHandler;
use Cajudev\PropertyAccessor;
use PHPUnit\Framework\TestCase;
use Cajudev\Exception\PropertyAccessException;
use Cajudev\Exception\PropertyHandlerException;

class PropertyHandlerTest extends TestCase {
	private $class;

	public function setUp() {
		$this->object = new class() {
			use PropertyAccessor;
		
			/** @Property(bool) */
			private $bool;
		};
	}

	public function test_should_throws_exception_when_get_invalid_property() {
		self::expectException(PropertyAccessException::class);
		$this->object->boool;
	}

	public function test_should_throws_exception_when_set_invalid_property() {
		self::expectException(PropertyAccessException::class);
		$this->object->boool = true;
	}

	public function test_should_throws_exception_when_get_not_registered_property() {
		self::expectException(PropertyHandlerException::class);
		$this->object->bool;
	}

	public function test_should_throws_exception_when_set_not_registered_property() {
		self::expectException(PropertyHandlerException::class);
		$this->object->bool = true;
	}

	public function test_should_throws_exception_when_get_registered_property_without_get_handler() {
		self::expectException(PropertyHandlerException::class);		
		PropertyHandler::registry('bool', []);
		$this->object->bool;
	}

	public function test_should_throws_exception_when_set_registered_property_without_set_handler() {
		self::expectException(PropertyHandlerException::class);		
		PropertyHandler::registry('bool', []);
		$this->object->bool = true;
	}

	public function test_should_throws_exception_when_registered_get_handler_is_not_callable() {
		self::expectException(PropertyHandlerException::class);		
		PropertyHandler::registry('bool', [
			'get' => 'NOT CALLABLE'
		]);
		$this->object->bool;
	}

	public function test_should_throws_exception_when_registered_set_handler_is_not_callable() {
		self::expectException(PropertyHandlerException::class);		
		PropertyHandler::registry('bool', [
			'set' => 'NOT CALLABLE'
		]);
		$this->object->bool = true;
	}

	public function test_should_validate_property_when_register_correctly() {
		PropertyHandler::registry('bool', [
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
}