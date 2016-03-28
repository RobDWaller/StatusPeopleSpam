<?php

class Commands
{
        protected $class;
        protected $method;

        public function __construct(array $argv)
        {
                empty($argv[1]) || empty($argv[2]) ? $this->fail('No arguments set') : true;

                $this->class = $argv[1];
                $this->method = $argv[2];
        }

        public function make()
        {
                $class = new $this->class();

                echo 'In class: '. $this->class.PHP_EOL;

                $method = $this->method;

                echo 'Executing method: '.$method.PHP_EOL;

                return $class->$method();
        }

        protected function fail($message = 'Process Failed!!')
        {
                echo 'Error: '.$message;
                die();
        }
}
