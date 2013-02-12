<?php

class ngOpenGraphObjectRelationList extends ngOpenGraphBase
{
    /**
     * Returns data for the attribute
     *
     * @return string
     */
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

    /**
     * Returns part of the data for the attribute
     *
     * @param string $dataMember
     *
     * @return string
     */
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
                        /** @var eZContentObjectAttribute $attribute */
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
