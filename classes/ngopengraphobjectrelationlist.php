<?php

class ngOpenGraphObjectRelationList extends ngOpenGraphBase
{
    function __construct( eZContentObjectAttribute $attribute )
    {
        parent::__construct( $attribute );
    }

    public function getData()
    {
        $return = array();
        $relations = $this->ContentObjectAttribute->attribute( 'content' );
        foreach ( $relations['relation_list'] as $relation )
        {
            $object = eZContentObject::fetch( $relation['contentobject_id'] );
            if ( $object instanceof eZContentObject )
            {
                $return[] = $object->attribute( 'name' );
            }
        }

        return implode( ', ', $return );
    }

    public function getDataMember( $dataMember )
    {
        if( $dataMember === 'related_images' )
        {
            $images = array();
            $relations = $this->ContentObjectAttribute->attribute( 'content' );
            foreach ( $relations['relation_list'] as $relation )
            {
                $object = eZContentObject::fetch( $relation['contentobject_id'] );
                if( $object instanceof eZContentObject )
                {
                    $dataMap = $object->attribute( 'data_map' );
                    foreach ( $dataMap as $attribute )
                    {
                        if ( $attribute->attribute( 'data_type_string' ) !== eZImageType::DATA_TYPE_STRING )
                        {
                            continue;
                        }

                        $imageAliasHandler = $attribute->attribute( 'content' );
                        $imageAlias = $imageAliasHandler->imageAlias( 'opengraph' );
                        if( $imageAlias['is_valid'] == 1 )
                        {
                            $images[] = eZSys::serverURL() . '/' . $imageAlias['full_path'];
                        }
                    }
                }
            }

            return $images;
        }

        return $this->getData();
    }
}
