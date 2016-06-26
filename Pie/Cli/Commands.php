<?php namespace Cli;

use Exception\CliException;
use Helpers\Cli;

class Commands
{
    use Cli;

    protected $class;
    protected $method;

    public function __construct(array $arguments)
    {
        $this->hasArguments($arguments) ? true : $this->fail('No arguments set') ;

        $this->class = $arguments[1];
        $this->method = $arguments[2];
    }

    public function make()
    {
        $class = new $this->class();

        echo 'In class: ' . $this->class . PHP_EOL;

        $method = $this->method;

        echo 'Executing method: ' . $method . PHP_EOL;

        return $class->$method();
    }

    protected function fail($message = 'Process Failed!!')
    {
        throw new CliException('Could not execute command because... [' . $message . ']');
    }
}
