<?php

declare(strict_types=1);

namespace Phel\Interop\Generator;

use Phel\Interop\ReadModel\FunctionToExport;
use Phel\Interop\ReadModel\Wrapper;

interface WrapperGeneratorInterface
{
    public function generateCompiledPhp(string $phelNs, FunctionToExport ...$functionsToExport): Wrapper;
}
