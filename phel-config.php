<?php

declare(strict_types=1);

return [
    'src-dirs' => ['src/phel'],
    'test-dirs' => ['tests/phel'],
    'vendor-dir' => 'vendor',

    'loader' => [
        'phel\\' => 'src/phel/',
    ],
    'loader-dev' => [
        'phel-test\\' => 'tests/phel/',
        'tests-phpbench\\' => 'tests/php/Benchmark/',
    ],
    'tests' => [
        'tests/phel/',
    ],
    'export' => [
        'directories' => ['src/phel'],
        'namespace-prefix' => 'PhelGenerated',
        'target-directory' => 'src/PhelGenerated',
    ],
];
