<?php

/*
 * This file is part of PHP-Compiler, a PHP CFG Compiler for PHP code
 *
 * @copyright 2015 Anthony Ferrara. All rights reserved
 * @license MIT See LICENSE at the root of the project for more info
 */

namespace PHPCompiler\VM;

// bug in phan: https://github.com/phan/phan/issues/2661
// @phan-suppress-next-line PhanUnreferencedUseNormal
use PHPCompiler\Block;

class ClassEntry {

    const PROP_PURPOSE_DEBUG = 1;

    public string $name;
    public ?Block $constructor = null;
    public array $properties = [];

    public function __construct(string $name) {
        $this->name = $name;
    }

    public function getProperties(array $properties, int $reason): array {
        // todo: implement __debug_info
        return $properties;
    }

}
