<?php

class ngOpenGraphGmapLocation extends ngOpenGraphBase
{
    /**
     * Constructor
     *
     * @param eZContentObjectAttribute $attribute
     */
    function __construct( eZContentObjectAttribute $attribute )
    {
        parent::__construct( $attribute );
    }

    /**
     * Returns data for the attribute
     *
     * @return string
     */
    public function getData()
    {
        $latitude = trim( $this->ContentObjectAttribute->attribute( 'content' )->attribute( 'latitude' ) );
        $longitude = trim( $this->ContentObjectAttribute->attribute( 'content' )->attribute( 'longitude' ) );
        return $latitude . ',' . $longitude;
    }

    /**
     * Returns part of the data for the attribute
     *
     * @param string $dataMember
     *
     * @return string
     */
    public function getDataMember( $dataMember )
    {
        return trim( $this->ContentObjectAttribute->attribute( 'content' )->attribute( $dataMember ) );
    }
}
