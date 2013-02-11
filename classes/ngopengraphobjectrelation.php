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

        if ( $relationObject instanceof eZContentObject )
        {
            return trim( $relationObject->attribute( 'name' ) );
        }

        return "";
    }

    public function getDataMember( $dataMember )
    {
        $relationObject = $this->ContentObjectAttribute->attribute( 'content' );

        if ( $relationObject instanceof eZContentObject )
        {
            if ( $dataMember === 'related_images' )
            {
                $images  = array();
                $dataMap = $relationObject->attribute( 'data_map' );
                foreach ( $dataMap as $attribute )
                {
                    if ( $attribute->attribute( 'data_type_string' ) !== eZImageType::DATA_TYPE_STRING )
                    {
                        continue;
                    }

                    $imageAliasHandler = $attribute->attribute( 'content' );
                    $imageAlias = $imageAliasHandler->imageAlias( 'opengraph' );
                    if ( $imageAlias['is_valid'] == 1 )
                    {
                        $images[] = eZSys::serverURL() . '/' . $imageAlias['full_path'];
                    }
                }

                return $images;
            }
        }

        return "";
    }
}
