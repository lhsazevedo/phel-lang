<?php

declare(strict_types=1);

namespace Phel\Compiler\Domain\Emitter;

use Phel\Compiler\Domain\Analyzer\Ast\AbstractNode;
use Phel\Compiler\Domain\Emitter\OutputEmitter\SourceMap\SourceMapGenerator;

final class StatementEmitter implements StatementEmitterInterface
{
    private SourceMapGenerator $sourceMapGenerator;
    private OutputEmitterInterface $outputEmitter;

    public function __construct(
        SourceMapGenerator $sourceMapGenerator,
        OutputEmitterInterface $outputEmitter,
    ) {
        $this->sourceMapGenerator = $sourceMapGenerator;
        $this->outputEmitter = $outputEmitter;
    }

    public function emitNode(AbstractNode $node, bool $enableSourceMaps): EmitterResult
    {
        $this->outputEmitter->resetIndentLevel();
        $this->outputEmitter->resetSourceMapState();

        $sourceLocation = $node->getStartSourceLocation();
        $file = $sourceLocation
            ? $sourceLocation->getFile()
            : 'string';

        ob_start();
        $this->outputEmitter->emitNode($node);
        $code = ob_get_clean();

        if (!$enableSourceMaps) {
            return new EmitterResult(
                $enableSourceMaps,
                $code,
                '',
                $file,
            );
        }

        $sourceMap = $this->sourceMapGenerator->encode(
            $this->outputEmitter->getSourceMapState()->getMappings(),
        );

        return new EmitterResult(
            $enableSourceMaps,
            $code,
            $sourceMap,
            $file,
        );
    }
}
