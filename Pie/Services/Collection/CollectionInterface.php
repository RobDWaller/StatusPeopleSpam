<?php namespace Services\Collection;

interface CollectionInterface
{
	public function rewind();

	public function current();

	public function key();

	public function next();

	public function valid();

	public function count();
    
    public function first();
}