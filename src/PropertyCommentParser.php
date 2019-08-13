<?php

namespace Cajudev;

class PropertyCommentParser {
    private $comment;

    public function __construct(string $comment) {
        $this->comment = $comment;
    }

    public function parse() {
        preg_match('/@Property\(\s*(?<type>\w+)\s*\)/', $this->comment, $match);
        return $match['type'] ?? null;
    }
}