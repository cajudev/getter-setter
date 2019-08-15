<?php

namespace Cajudev;

use Cajudev\Exception\GetterSetterException;

class GetterSetter {
	private static $handlers = [];

	public static function registry(string $name, array $handler) {
		self::$handlers[$name] = array_change_key_case($handler, CASE_LOWER);
	}

	public static function get(\ReflectionProperty $property, $value) {
		return self::exec($property, $value, 'get');
	}

	public static function set(\ReflectionProperty $property, $value) {
		return self::exec($property, $value, 'set');
	}

	private static function exec(\ReflectionProperty $property, $value, string $action) {
		$parser = new GetterSetterCommentParser($property->getDocComment());
		$type   = $parser->parse();

		if (is_null($type)) {
			return $value;
		}

		if (!isset(self::$handlers[$type])) {
			throw new GetterSetterException("GetterSetter [$type] wasn't registered");
		}

		if (!isset(self::$handlers[$type][$action])) {
			throw new GetterSetterException("{$action} method to GetterSetter [$type] wasn't registered");
		}

		if (!is_callable(self::$handlers[$type][$action])) {
			throw new GetterSetterException("{$action} method to GetterSetter [$type] must be a callable");
		}

		return self::$handlers[$type][$action]($value);
	}
}