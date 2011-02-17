<?php

class ngOpenGraphGmapLocation extends ngOpenGraphBase
{
	function __construct( eZContentObjectAttribute $attribute )
	{
		parent::__construct( $attribute );
	}

	public function getData()
	{
		$latitude = trim($this->ContentObjectAttribute->attribute( 'content' )->attribute('latitude'));
		$longitude = trim($this->ContentObjectAttribute->attribute( 'content' )->attribute('longitude'));
		return $latitude . ',' . $longitude;
	}

	public function getDataMember( $dataMember )
	{
		return trim($this->ContentObjectAttribute->attribute( 'content' )->attribute($dataMember));
	}
}

?>