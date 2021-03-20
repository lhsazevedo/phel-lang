<?php

declare(strict_types=1);

namespace Phel\Compiler;

use Gacela\AbstractFactory;
use Phel\Compiler\Analyzer\Analyzer;
use Phel\Compiler\Analyzer\AnalyzerInterface;
use Phel\Compiler\Emitter\Emitter;
use Phel\Compiler\Emitter\EmitterInterface;
use Phel\Compiler\Emitter\EvalEmitter;
use Phel\Compiler\Emitter\EvalEmitterInterface;
use Phel\Compiler\Emitter\OutputEmitter;
use Phel\Compiler\Emitter\OutputEmitter\Munge;
use Phel\Compiler\Emitter\OutputEmitter\NodeEmitterFactory;
use Phel\Compiler\Emitter\OutputEmitter\SourceMap\SourceMapGenerator;
use Phel\Compiler\Emitter\OutputEmitterInterface;
use Phel\Compiler\Lexer\Lexer;
use Phel\Compiler\Lexer\LexerInterface;
use Phel\Compiler\Parser\ExpressionParserFactory;
use Phel\Compiler\Parser\Parser;
use Phel\Compiler\Parser\ParserInterface;
use Phel\Compiler\Reader\ExpressionReaderFactory;
use Phel\Compiler\Reader\QuasiquoteTransformer;
use Phel\Compiler\Reader\Reader;
use Phel\Compiler\Reader\ReaderInterface;
use Phel\Printer\Printer;
use Phel\Runtime\RuntimeFacadeInterface;

final class CompilerFactory extends AbstractFactory
{
    public function createEvalCompiler(): EvalCompilerInterface
    {
        return new EvalCompiler(
            $this->createLexer(),
            $this->createParser(),
            $this->createReader(),
            $this->createAnalyzer(),
            $this->createEmitter()
        );
    }

    public function createFileCompiler(): FileCompilerInterface
    {
        return new FileCompiler(
            $this->createLexer(),
            $this->createParser(),
            $this->createReader(),
            $this->createAnalyzer(),
            $this->createEmitter()
        );
    }

    public function createLexer(bool $withLocation = true): LexerInterface
    {
        return new Lexer($withLocation);
    }

    public function createReader(): ReaderInterface
    {
        $runtime = $this->getRuntimeFacade()->getRuntime();

        return new Reader(
            new ExpressionReaderFactory(),
            new QuasiquoteTransformer($runtime->getEnv())
        );
    }

    public function createParser(): ParserInterface
    {
        return new Parser(
            new ExpressionParserFactory()
        );
    }

    public function createAnalyzer(): AnalyzerInterface
    {
        $runtime = $this->getRuntimeFacade()->getRuntime();

        return new Analyzer($runtime->getEnv());
    }

    public function createEmitter(bool $enableSourceMaps = true): EmitterInterface
    {
        return new Emitter(
            $this->createOutputEmitter($enableSourceMaps),
            $this->createEvalEmitter()
        );
    }

    public function createOutputEmitter(bool $enableSourceMaps = true): OutputEmitterInterface
    {
        return new OutputEmitter(
            $enableSourceMaps,
            new SourceMapGenerator(),
            new NodeEmitterFactory(),
            new Munge(),
            Printer::readable()
        );
    }

    private function createEvalEmitter(): EvalEmitterInterface
    {
        return new EvalEmitter();
    }

    private function getRuntimeFacade(): RuntimeFacadeInterface
    {
        return $this->getProvidedDependency(CompilerDependencyProvider::FACADE_RUNTIME);
    }
}
