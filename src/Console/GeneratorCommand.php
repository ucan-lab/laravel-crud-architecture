<?php

declare(strict_types=1);

namespace UcanLab\LaravelArchitecture\Console;

use Illuminate\Console\GeneratorCommand as BaseGeneratorCommand;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;

abstract class GeneratorCommand extends BaseGeneratorCommand
{
    /**
     * Get the stub for the generator.
     *
     * @return string
     */
    abstract protected function getStubName(): string;

    /**
     * Get the namespace for the class.
     *
     * @return string
     */
    abstract protected function getNamespaceName(): string;

    /**
     * Get the default namespace for the class.
     *
     * @param string $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace . '\\' . $this->getNamespaceName();
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name): string
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);
        $name = config('architecture.rootDir') . $name;

        return base_path() . '/' . str_replace('\\', '/', $name) . '.php';
    }

    /**
     * Build the class with the given name.
     *
     * @param string $name
     * @return string
     *
     * @throws FileNotFoundException
     */
    protected function buildClass($name): string
    {
        $stub = $this->files->get($this->getStub());

        return $this
            ->replaceDeclareStrictTypes($stub)
            ->replaceNamespace($stub, $name)
            ->replaceImports($stub, $name)
            ->replaceClass($stub, $name);
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub(): string
    {
        return $this->resolveStubPath('/stubs/' . $this->getStubName());
    }

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param string $stub
     * @return string
     */
    protected function resolveStubPath(string $stub): string
    {
        return file_exists($customPath = $this->laravel->basePath(trim($stub, '/')))
            ? $customPath
            : __DIR__ . $stub;
    }

    /**
     * Replace the namespace for the given stub.
     *
     * @param string $stub
     * @return $this
     */
    protected function replaceDeclareStrictTypes(string &$stub): self
    {
        $search = '{{ declareStrictTypes }}';

        $replace = '';
        if (config('architecture.declareStrictTypes')) {
            $replace = PHP_EOL . 'declare(strict_types=1);' . PHP_EOL;
        }

        $stub = str_replace($search, $replace, $stub);

        return $this;
    }

    /**
     * Replace the imports for the given stub.
     *
     * @param string $stub
     * @param string $name
     * @return $this
     */
    protected function replaceImports(string &$stub, string $name): self
    {
        $search = '{{ imports }}';

        $stub = str_replace($search, $this->getImports(), $stub);

        return $this;
    }

    /**
     * @return string
     */
    protected function getImports(): string
    {
        return '';
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace(): string
    {
        return config('architecture.rootNamespace');
    }
}
