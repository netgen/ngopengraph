<?php

class ngOpenGraphXml extends ngOpenGraphBase
{
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
