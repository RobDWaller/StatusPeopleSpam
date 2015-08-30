<?php namespace Services\Html;

use HtmlObject\Input;
use Services\Html\Base;
use HtmlObject\Element;

class Forms extends Base
{
	protected $form;
	protected $inputs;
	protected $label;
	protected $select;
	protected $options;
	
	public function open($attributes = null)
	{
		$this->inputs = array();
		$this->options = array();
		$this->form = Element::form();
		$this->addAttributes($this->form, $attributes);
		return $this;
	}

	protected function isSelected($element, $data, $value, $selected)
	{
		if ($data[$value]==$selected) {
			$this->addAttributes($element,['selected'=>'true']);
		}
	}

	public function input($type, $name = null, $attributes = array(), $label = null, $for = null, $value = null)
	{
		if ($type!='hidden') {	
			$this->inputs[] = $this->buildFieldset(
				$this->addLabel($label,$for),Input::create($type, $name, $value, $attributes)
			);

			return $this;
		}
		
		$this->inputs[] = Input::create($type, $name, $value, $attributes);
		
		return $this;
	}

	public function options(
		$data, 
		$name = null, 
		$attributes = null, 
		$label = null, 
		$for = null, 
		$text = null, 
		$value = null, 
		$selected = null
	)
	{
		$this->select = Element::select();
		$this->addAttributes($this->select,$attributes);

		foreach ($data as $key => $v) {
			$option = Element::option($v[$text]);
			
			$this->addAttributes(
				$option,
				['Value'=>$v[$value]]
			);
			
			$this->isSelected(
				$option,
				$v,
				$value,
				$selected
			);

			$this->options[] = $option;
		}

		
		$this->select->nest($this->options);

		$this->inputs[] = $this->buildFieldset($this->addLabel($label,$for),$this->select);

		return $this;
	}

	public function buildFieldset($label,$input)
	{
		return Element::fieldset()->nest([$label,$input]);
	}

	public function addLabel($text,$for)
	{
		$this->label = '';

		if ($text != null)
		{
			$this->label = Element::label($text);

			$this->addAttributes($this->label,['for'=>$for]);
		}

		return $this->label;
	}

	public function build()
	{
		return $this->form->nest($this->inputs);
	}

	public function clean()
	{

	}
}