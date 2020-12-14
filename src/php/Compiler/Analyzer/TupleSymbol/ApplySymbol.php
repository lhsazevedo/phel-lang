<?php

declare(strict_types=1);

namespace Phel\Compiler\Analyzer\TupleSymbol;

use Phel\Compiler\Analyzer\WithAnalyzer;
use Phel\Compiler\Ast\ApplyNode;
use Phel\Compiler\Ast\Node;
use Phel\Compiler\NodeEnvironmentInterface;
use Phel\Exceptions\AnalyzerException;
use Phel\Lang\AbstractType;
use Phel\Lang\Tuple;

final class ApplySymbol implements TupleSymbolAnalyzer
{
    use WithAnalyzer;

    public function analyze(Tuple $tuple, NodeEnvironmentInterface $env): ApplyNode
    {
        if (count($tuple) < 3) {
            throw AnalyzerException::withLocation("At least three arguments are required for 'apply", $tuple);
        }

        return new ApplyNode(
            $env,
            $this->fnExpr($tuple[1], $env),
            $this->arguments($tuple, $env),
            $tuple->getStartLocation()
        );
    }

    /**
     * Analyze the function expression of the apply special form.
     *
     * @param AbstractType|string|float|int|bool|null $x
     * @param NodeEnvironmentInterface $env
     *
     * @return Node
     */
    private function fnExpr($x, NodeEnvironmentInterface $env): Node
    {
        return $this->analyzer->analyze(
            $x,
            $env->withContext(NodeEnvironmentInterface::CONTEXT_EXPRESSION)->withDisallowRecurFrame()
        );
    }

    private function arguments(Tuple $x, NodeEnvironmentInterface $env): array
    {
        $args = [];
        for ($i = 2, $iMax = count($x); $i < $iMax; $i++) {
            $args[] = $this->fnExpr($x[$i], $env);
        }

        return $args;
    }
}