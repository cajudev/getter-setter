<?php

namespace Cajudev;

use Cajudev\Exception\GetterSetterAccessException;

trait GetterSetterAccessor {
	public function __get(string $name) {
		try {
			$property = new \ReflectionProperty($this, $name);
			return GetterSetter::get($property, $this->$name);
		} catch (\ReflectionException $e) {
			throw new GetterSetterAccessException("Property {$name} not exists");
		}
	}
	
    public function __set(string $name, $value) {
		try {
			$property = new \ReflectionProperty($this, $name);
			$this->$name = GetterSetter::set($property, $value);
		} catch (\ReflectionException $e) {
			throw new GetterSetterAccessException("Property {$name} not exists");
		}
	}
}