<?php

declare(strict_types=1);

namespace Phel\Printer\TypePrinter;

use Phel\Printer\PrinterInterface;

use function count;

/**
 * @implements TypePrinterInterface<array>
 */
final class ArrayPrinter implements TypePrinterInterface
{
    public function __construct(
        private PrinterInterface $printer,
        private bool $withColor = false,
    ) {
    }

    /**
     * @param array $form
     */
    public function print(mixed $form): string
    {
        $arr = $this->isList($form)
            ? $this->formatValuesFromList($form)
            : $this->formatKeyValuesFromDict($form);

        return sprintf('<PHP-Array [%s]>', $this->color(implode(', ', $arr)));
    }

    private function isList(array $form): bool
    {
        return array_keys($form) === range(0, count($form) - 1);
    }

    private function formatValuesFromList(array $form): array
    {
        return array_map(
            fn ($v) => $this->printer->print($v),
            $form,
        );
    }

    private function formatKeyValuesFromDict(array $form): array
    {
        return array_map(
            fn ($k, $v) => sprintf('%s:%s', $this->printer->print($k), $this->printer->print($v)),
            array_keys($form),
            $form,
        );
    }

    private function color(string $str): string
    {
        if ($this->withColor) {
            return sprintf("\033[0;37m%s\033[0m", $str);
        }

        return $str;
    }
}
