<?php

class ngOpenGraphXml extends ngOpenGraphBase
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
        return str_replace( "\n", " ", strip_tags( trim( $this->ContentObjectAttribute->attribute( 'data_text' ) ) ) );
    }
}
