<?php

declare(strict_types=1);

namespace PhelTest\Unit\Interop\Generator\Builder;

use Generator;
use Phel\Interop\Generator\Builder\WrapperRelativeFilenamePathBuilder;
use PHPUnit\Framework\TestCase;

final class WrapperRelativeFilenamePathBuilderTest extends TestCase
{
    /**
     * @dataProvider providerBuild
     */
    public function testBuild(string $phelNs, string $expected): void
    {
        $builder = new WrapperRelativeFilenamePathBuilder();

        self::assertSame($expected, $builder->build($phelNs));
    }

    public function providerBuild(): Generator
    {
        yield 'simple name' => [
            'phelNs' => 'project/simple',
            'expected' => 'Project/Simple.php',
        ];

        yield 'filename with dash' => [
            'phelNs' => 'project/the-file',
            'expected' => 'Project/TheFile.php',
        ];

        yield 'filename and phel namespace with dash' => [
            'phelNs' => 'the-project/the-simple-file',
            'expected' => 'TheProject/TheSimpleFile.php',
        ];
    }
}
