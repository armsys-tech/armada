<?php
// Run: php tests/tag-compiler.php

require __DIR__.'/../src/TagCompiler.php';

use Armada\TagCompiler;

$cases = [
    '<armada:button>Kaydet</armada:button>' => '<x-armada::button>Kaydet</x-armada::button>',
    '<armada:input wire:model="email" />' => '<x-armada::input wire:model="email" />',
    '<armada:navlist.item href="/">' => '<x-armada::navlist.item href="/">',
    '<armada:theme-switcher/>' => '<x-armada::theme-switcher/>',
    "</armada:button\n>" => '</x-armada::button>',
    '<div class="armada:not-a-tag">' => '<div class="armada:not-a-tag">',
    '<x-armada::button>' => '<x-armada::button>',
];

foreach ($cases as $in => $expected) {
    $out = TagCompiler::compile($in);
    if ($out !== $expected) {
        fwrite(STDERR, "FAIL: $in\n  got:      $out\n  expected: $expected\n");
        exit(1);
    }
}

echo 'OK '.count($cases)." cases\n";
