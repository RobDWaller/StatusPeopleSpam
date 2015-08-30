<?php namespace Services\Object;

class Builder
{
	public function ArrayToObject($array)
	{
		$object = new \stdClass();

		foreach ($array as $key => $value) {

			$object->$key = $value;

		}

		return $object;
	}
}