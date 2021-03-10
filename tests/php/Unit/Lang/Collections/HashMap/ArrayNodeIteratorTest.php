<?php

declare(strict_types=1);

namespace PhelTest\Unit\Lang\Collections\HashMap;

use Phel\Lang\Collections\HashMap\ArrayNode;
use Phel\Lang\Collections\HashMap\Box;
use PhelTest\Benchmark\Lang\Collections\HashMap\SimpleEqualizer;
use PHPUnit\Framework\TestCase;

class ArrayNodeIteratorTest extends TestCase
{
    public function testIterateOnEmptyNode(): void
    {
        $node = ArrayNode::empty(new ModuloHasher(), new SimpleEqualizer());

        $result = [];
        foreach ($node as $k => $v) {
            $result[$k] = $v;
        }

        $this->assertEmpty($result);
    }

    public function testIterateOnSingleEntryNode(): void
    {
        $node = ArrayNode::empty(new ModuloHasher(), new SimpleEqualizer())
            ->put(0, 1, 1, 'foo', new Box(false));

        $result = [];
        foreach ($node as $k => $v) {
            $result[$k] = $v;
        }

        $this->assertEquals([1 => 'foo'], $result);
    }

    public function testIterateOnTwoEntryNode(): void
    {
        $node = ArrayNode::empty(new ModuloHasher(), new SimpleEqualizer())
            ->put(0, 1, 1, 'foo', new Box(false))
            ->put(0, 2, 2, 'bar', new Box(false));

        $result = [];
        foreach ($node as $k => $v) {
            $result[$k] = $v;
        }

        $this->assertEquals([1 => 'foo', 2 => 'bar'], $result);
    }
}
