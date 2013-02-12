<?php

class ngOpenGraphText extends ngOpenGraphBase
{
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
