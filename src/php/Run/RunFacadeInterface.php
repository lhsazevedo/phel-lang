<?php

declare(strict_types=1);

namespace Phel\Run;

use Phel\Run\Command\Repl\ReplCommand;
use Phel\Run\Command\RunCommand;
use Phel\Run\Command\Test\TestCommand;

interface RunFacadeInterface
{
    public function getReplCommand(): ReplCommand;

    public function getRunCommand(): RunCommand;

    public function getTestCommand(): TestCommand;

    public function runNamespace(string $namespace): void;
}
