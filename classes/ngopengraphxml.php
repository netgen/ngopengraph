<?php

class ngOpenGraphXml extends ngOpenGraphBase
{
    function __construct( eZContentObjectAttribute $attribute )
    {
        parent::__construct( $attribute );
    }

    public function getData()
    {
        return str_replace( "\n", " ", strip_tags( trim( $this->ContentObjectAttribute->attribute( 'data_text' ) ) ) );
    }
}
