<?php namespace Services\Messages;

interface MessagesInterface
{
	public function hasId();

	public function getType();

	public function getMessages();

	public function getId(); 
}