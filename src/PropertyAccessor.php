<?php

namespace Cajudev;

use Cajudev\Exception\PropertyAccessException;

trait PropertyAccessor {
	public function __get(string $name) {
		if (!property_exists($this, $name)) {
			throw new PropertyAccessException("Property {$name} not exists");
		}
		$property = new \ReflectionProperty($this, $name);
		return PropertyHandler::get($property, $this->$name);
	}
	
    public function __set(string $name, $value) {
		if (!property_exists($this, $name)) {
			throw new PropertyAccessException("Property {$name} not exists");
		}
		$property = new \ReflectionProperty($this, $name);
		$this->$name = PropertyHandler::set($property, $value);
	}
}