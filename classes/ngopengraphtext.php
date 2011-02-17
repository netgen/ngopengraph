<?php

class ngOpenGraphText extends ngOpenGraphBase
{
	function __construct( eZContentObjectAttribute $attribute )
	{
		parent::__construct( $attribute );
	}

	public function getData()
	{
		return str_replace("\n", " ", trim($this->ContentObjectAttribute->attribute('data_text')));
	}
}

?>