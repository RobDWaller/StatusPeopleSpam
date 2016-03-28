<?php namespace Services\Messages;

use Services\Collection\CollectionInterface;
use Services\Html\Lists;
use HtmlObject\Element;
use Services\Messages\Factory;

class Html
{
	protected $messages;

	public function __construct(CollectionInterface $messageCollection)
	{
		$this->messages = $messageCollection;
	}

	public function buildMessages()
	{
		if ($this->hasMessages()) {
			return $this->createMessageDiv();
		}

		return null;
	} 

	protected function createMessageDiv()
	{
 		foreach ($this->messages as $key => $mesObj) {
 			$list = $this->createMessageList($mesObj);

 			$icon = $this->createIcon($mesObj);

 			$close = $this->closeButton($mesObj); 

 			$holder = Element::div($icon.$list.$close, ['class' => $mesObj->getType().'message bree', 'id' => $mesObj->getType().'message']);

 			$html .= $holder->build(); 
 		}

 		return $html;
	}

	protected function createIcon($message)
	{
		return Element::div(
			Element::img('', ['src' => '/Pie/Crust/Template/img/'.$message->getType().'_icon.png']), 
			['class' => 'e1']
		);
	}

	protected function closeButton($message)
	{
		return Element::div(
			Element::span('X', ['class' => $message->getType().'close', 'id' => $message->getType().'close']), 
			['class' => $message->getType().'close s0 e3']
		);
	}

	protected function createMessageList(Factory $messages)
	{
		$list = new Lists();	

		$list->open();

		$list = $this->createMessage($list, $messages);

		$messageList = $list->build();

		$messageListHolder = Element::div($messageList, ['class' => 'e2']);

		$list->clean();

		return $messageListHolder;
	}

	protected function createMessage($list, $messages)
	{
		$list = new Lists();

		$list->open();

		foreach ($messages->getMessages() as $key => $value) {
			$list->addItem($value);
		}

		return $list;
	}

	protected function hasMessages()
	{
		return $this->messages->count();
	} 

}