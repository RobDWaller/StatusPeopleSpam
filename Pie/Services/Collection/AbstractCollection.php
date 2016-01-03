<?php namespace Services\Collection;

use \Iterator;
use \Countable;
use Services\Collection\CollectionInterface;

abstract class AbstractCollection implements Iterator, Countable, CollectionInterface
{
	protected $records;

	public function __construct(array $records) {
        $this->records = $records;
	}

	public function rewind()
    {
        reset($this->records);
    }

    public function current()
    {
        return current($this->records);
    }

    public function key()
    {
        return key($this->records);
    }

    public function next()
    {
        return next($this->records);
    }

    public function valid()
    {
        return false !== current($this->records);
    }	

    public function count()
    {
    	return count($this->records);
    }

    public function first()
    {
    	$this->rewind();

    	return $this->current();
    }
}