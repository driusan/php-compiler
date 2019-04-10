<?php

/*
 * This file is part of PHP-Compiler, a PHP CFG Compiler for PHP code
 *
 * @copyright 2015 Anthony Ferrara. All rights reserved
 * @license MIT See LICENSE at the root of the project for more info
 */

namespace PHPCompiler\VM;

use PHPCompiler\Frame;
use PHPCompiler\Func;
use PHPCompiler\Runtime;
use PHPCompiler\Handler\Builtins;
use PHPTypes\Type;

class Context {
    public array $functions = [];
    public array $classes = [];
    private ?RunStackEntry $runStack = null;
    public array $constants = [];

    public Runtime $runtime;
    

    public function __construct(Runtime $runtime) {
        $this->runtime = $runtime;
    }

    public function constantFetch(string $name): ?Variable {
        switch (strtolower($name)) {
            case 'null':
                return new Variable(Variable::TYPE_NULL);
            case 'false':
                $var = new Variable(Variable::TYPE_BOOLEAN, false);
                return $var;
            case 'true':
                $var = new Variable(Variable::TYPE_BOOLEAN, true);
                return $var;
        }
        if (isset($this->constants[$name])) {
            return $this->constants[$name];
        }
        return null;
    }

    public function declareFunction(Func $func): void {
        $lcname = strtolower($func->getName());
        $this->functions[$lcname] = $func;
    }

    public function save(Frame $frame): RunStackEntry {
        $this->push($frame);
        $return = $this->runStack;
        $this->runStack = null;
        return $return;
    }

    public function restore(RunStackEntry $runStack): Frame {
        assert(is_null($this->runStack));
        $this->runStack = $runStack->prev;
        return $runStack->frame;
    }

    public function push(Frame $frame): void {
        if (is_null($this->runStack)) {
            $this->runStack = new RunStackEntry($frame);
        } else {
            $this->runStack = $this->runStack->prev = new RunStackEntry($frame);
        }
    }

    public function pop(): ?Frame {
        $return = $this->runStack;
        if (!is_null($this->runStack)) {
            $this->runStack = $this->runStack->prev;
            return $return->frame;
        }
        return null;;
    }
}

class RunStackEntry {
    public ?RunStackEntry $prev = null; 
    public Frame $frame;

    public function __construct(Frame $frame) {
        $this->frame = $frame;
    }
}
