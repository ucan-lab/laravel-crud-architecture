<?php

declare(strict_types=1);

namespace UcanLab\LaravelArchitecture\Console;

use Symfony\Component\Console\Input\InputOption;

final class OnionPresentationRequestCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'onion:presentation:request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'onion:presentation:request';

    /**
     * Get the stub for the generator.
     *
     * @return string
     */
    protected function getStubName(): string
    {
        return 'onion.request.stub';
    }

    /**
     * Get the namespace for the class.
     *
     * @return string
     */
    protected function getNamespaceName(): string
    {
        return config('architecture.onion.presentation.request.namespace');
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions(): array
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Create the class even if the request already exists'],
        ];
    }
}
