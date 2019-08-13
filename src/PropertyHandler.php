<?php

namespace Cajudev;

use Cajudev\Exception\PropertyHandlerException;

class PropertyHandler {
	private static $handlers = [];

	public static function registry(string $name, array $handler) {
		self::$handlers[$name] = $handler;
	}

	public static function get(\ReflectionProperty $property, $value) {
		return self::exec($property, $value, 'get');
	}

	public static function set(\ReflectionProperty $property, $value) {
		return self::exec($property, $value, 'set');
	}

	private static function exec(\ReflectionProperty $property, $value, string $action) {
		$parser = new PropertyCommentParser($property->getDocComment());
		$type   = $parser->parse();

		if (is_null($type)) {
			return $value;
		}

		if (!isset(self::$handlers[$type])) {
			throw new PropertyHandlerException("Handler [$type] wasn't registered");
		}

		if (!isset(self::$handlers[$type][$action])) {
			throw new PropertyHandlerException("{$action} method to handler [$type] wasn't registered");
		}

		if (!is_callable(self::$handlers[$type][$action])) {
			throw new PropertyHandlerException("{$action} method to handler [$type] must be a callable");
		}

		return self::$handlers[$type][$action]($value);
	}
}