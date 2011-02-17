<?php

class ngOpenGraphObjectRelation extends ngOpenGraphBase
{
	function __construct( eZContentObjectAttribute $attribute )
	{
		parent::__construct( $attribute );
	}

	public function getData()
	{
		$relationObject = $this->ContentObjectAttribute->attribute( 'content' );
		
		if($relationObject instanceof eZContentObject)
		{
			return trim($relationObject->attribute('name'));
		}
		
		return "";
	}
}

?>