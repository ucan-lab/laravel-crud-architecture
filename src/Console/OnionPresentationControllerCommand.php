<?php

declare(strict_types=1);

namespace UcanLab\LaravelArchitecture\Console;

use Symfony\Component\Console\Input\InputOption;

final class OnionPresentationControllerCommand extends GeneratorCommand
{
    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'onion:presentation:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'onion:presentation:controller';

    /**
     * Get the stub for the generator.
     *
     * @return string
     */
    protected function getStubName(): string
    {
        return 'onion.controller.stub';
    }

    /**
     * Get the namespace for the class.
     *
     * @return string
     */
    protected function getNamespaceName(): string
    {
        return config('architecture.onion.presentation.controller.namespace');
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in the base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name): string
    {
        $controllerNamespace = $this->getNamespace($name);

        $replace = [];

        if ($this->option('parent')) {
            $replace = $this->buildParentReplacements();
        }

        if ($this->option('model')) {
            $replace = $this->buildModelReplacements($replace);
        }

        $replace["use {$controllerNamespace}\Controller;\n"] = '';

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    /**
     * @return string
     */
    protected function getImports(): string
    {
        return PHP_EOL . implode(PHP_EOL, [
             $this->getRequestClassName(),
             $this->getUseCaseClassName(),
             $this->getUseCaseInputClassName(),
        ]);
    }

    /**
     * @return string
     */
    protected function getRequestClassName(): string
    {
        return sprintf('use %s%s\\%sRequest;', $this->rootNamespace(), config('architecture.onion.presentation.request.namespace'), $this->getBaseName());
    }

    /**
     * @return string
     */
    protected function getUseCaseClassName(): string
    {
        return sprintf('use %s%s\\%sUseCase;', $this->rootNamespace(), config('architecture.onion.application.useCase.namespace'), $this->getBaseName());
    }

    /**
     * @return string
     */
    protected function getUseCaseInputClassName(): string
    {
        return sprintf('use %s%s\\%sInput;', $this->rootNamespace(), config('architecture.onion.application.useCaseInput.namespace'), $this->getBaseName());
    }

    /**
     * @return string
     */
    protected function getBaseName(): string
    {
        return str_replace('Controller', '', $this->getNameInput());
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Create the class even if the controller already exists'],
        ];
    }
}
