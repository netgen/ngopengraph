<?php

class ngOpenGraphBase
{
	function __construct( eZContentObjectAttribute $attribute )
	{
		$this->ContentObjectAttribute = $attribute;
	}

    static function getInstance( eZContentObjectAttribute $objectAttribute )
    {
		$datatypeString = $objectAttribute->attribute( 'data_type_string' );
		$dataTypeHandlers = self::$ogIni->variable( 'OpenGraph', 'DataTypeHandlers' );

		if ( array_key_exists( $datatypeString, $dataTypeHandlers ) )
		{
			if ( class_exists( $dataTypeHandlers[$datatypeString] ) )
			{
				return new $dataTypeHandlers[$datatypeString]( $objectAttribute );
			}
		}
 
		return new ngOpenGraphBase( $objectAttribute );
	}

	public function getData()
	{
		return trim($this->ContentObjectAttribute->attribute('data_text'));
	}

	public function getDataMember( $dataMember )
	{
		return $this->getData();
	}
	
	public $ContentObjectAttribute;

	static $ogIni;
}

ngOpenGraphBase::$ogIni = eZINI::instance( 'ngopengraph.ini' );

?>