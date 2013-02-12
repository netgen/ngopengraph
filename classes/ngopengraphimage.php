<?php

class ngOpenGraphImage extends ngOpenGraphBase
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
        $imageAliasHandler = $this->ContentObjectAttribute->attribute( 'content' );
        $imageAlias = $imageAliasHandler->imageAlias( 'opengraph' );

        if ( $imageAlias['is_valid'] == 1 )
        {
            return eZSys::serverURL() . '/' . $imageAlias['full_path'];
        }

        return eZSys::serverURL() . eZURLOperator::eZImage( null, 'opengraph_default_image.png', '' );
    }
}
