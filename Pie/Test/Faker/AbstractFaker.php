<?php namespace Test\Faker;

use Faker\Factory;

abstract class AbstractFaker
{
	protected $faker;

	public function __construct()
	{
		$this->faker = Factory::create();
	}
}