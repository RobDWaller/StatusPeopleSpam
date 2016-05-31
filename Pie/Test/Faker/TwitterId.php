<?php namespace Test\Faker;

use Services\Twitter\Object\Id;
use Services\Twitter\Object\Ids;
use Fakers\Score\Object\FollowerIdGroup;

class TwitterId extends AbstractFaker
{
	public function getId()
	{
		return new Id($this->faker->numberBetween(1, 1000000));
	}

	public function getIds($count = 1)
	{
		$c = 1;

		while($c <= $count) {

			$idArray[] = $this->getId();

			$c++;
		}

		return new Ids($idArray);
	}

	public function getIdGroups($count = 1)
	{
		$c = 1;

		while($c <= $count) {

			$idArray[] = $this->getIds(5000);

			$c++;
		}

		return new FollowerIdGroup($idArray);
	}

	public function getHundredIdGroups($followers = 1)
	{
		$count = round($followers/100);

		$c = 1;

		while($c <= $count) {
			$idArray[] = $this->getIds(100);

			$c++;
		}

		return new FollowerIdGroup($idArray);
	}
}