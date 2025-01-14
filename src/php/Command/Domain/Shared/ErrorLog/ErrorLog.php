<?php

declare(strict_types=1);

namespace Phel\Command\Domain\Shared\ErrorLog;

final class ErrorLog implements ErrorLogInterface
{
    public function __construct(
        private string $filepath,
    ) {
    }

    public function writeln(string $text): void
    {
        file_put_contents(
            $this->filepath,
            $text . PHP_EOL,
            FILE_APPEND | LOCK_EX,
        );
    }
}
