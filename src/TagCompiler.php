<?php

namespace Armada;

/**
 * Rewrites <armada:button> tags to <x-armada::button> before Blade compiles component tags.
 */
final class TagCompiler
{
    public static function compile(string $value): string
    {
        // shortcut: also rewrites tags inside @verbatim; revisit when the docs need to print literal tags.
        return preg_replace(
            ['/<\s*armada:([\w.\-]+)/', '/<\/\s*armada:([\w.\-]+)\s*>/'],
            ['<x-armada::$1', '</x-armada::$1>'],
            $value,
        );
    }
}
