<?php

declare(strict_types=1);

namespace Phel\Interop\Domain\Generator;

use Phel\Interop\Domain\Generator\Builder\CompiledPhpClassBuilder;
use Phel\Interop\Domain\Generator\Builder\WrapperRelativeFilenamePathBuilder;
use Phel\Interop\Domain\ReadModel\FunctionToExport;
use Phel\Interop\Domain\ReadModel\Wrapper;

final class WrapperGenerator implements WrapperGeneratorInterface
{
    private CompiledPhpClassBuilder $classBuilder;
    private WrapperRelativeFilenamePathBuilder $relativeFilenamePathBuilder;

    public function __construct(
        CompiledPhpClassBuilder $classBuilder,
        WrapperRelativeFilenamePathBuilder $relativeFilenamePathBuilder,
    ) {
        $this->classBuilder = $classBuilder;
        $this->relativeFilenamePathBuilder = $relativeFilenamePathBuilder;
    }

    /**
     * @param list<FunctionToExport> $functionsToExport
     */
    public function generateCompiledPhp(string $phelNs, array $functionsToExport): Wrapper
    {
        $relativeFilenamePath = $this->relativeFilenamePathBuilder->build($phelNs);
        $compiledPhpClass = $this->classBuilder->build($phelNs, $functionsToExport);

        return new Wrapper($relativeFilenamePath, $compiledPhpClass);
    }
}
