<?php

class ngOpenGraphText extends ngOpenGraphBase
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
        return str_replace( "\n", " ", trim( $this->ContentObjectAttribute->attribute( 'data_text' ) ) );
    }
}
