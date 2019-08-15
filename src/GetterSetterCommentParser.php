<?php

namespace Cajudev;

class GetterSetterCommentParser {
    private $comment;

    public function __construct(string $comment) {
        $this->comment = $comment;
    }

    public function parse() {
        preg_match('/@GetterSetter\(\s*(?<type>\w+)\s*\)/', $this->comment, $match);
        return $match['type'] ?? null;
    }
}