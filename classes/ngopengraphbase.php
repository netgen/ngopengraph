<?php

class ngOpenGraphBase
{
    /**
     * Constructor
     *
     * @param eZContentObjectAttribute $attribute
     */
    function __construct( eZContentObjectAttribute $attribute )
    {
        $this->ContentObjectAttribute = $attribute;
    }

    /**
     * Gets the instance of Open Graph attribute handler for the attribute
     *
     * @param eZContentObjectAttribute $objectAttribute
     *
     * @return ngOpenGraphBase
     */
    static function getInstance( eZContentObjectAttribute $objectAttribute )
    {
        $datatypeString = $objectAttribute->attribute( 'data_type_string' );
        $dataTypeHandlers = self::$ogIni->variable( 'OpenGraph', 'DataTypeHandlers' );

        if ( array_key_exists( $datatypeString, $dataTypeHandlers ) )
        {
            if ( class_exists( $dataTypeHandlers[$datatypeString] ) )
            {
                return new $dataTypeHandlers[$datatypeString]( $objectAttribute );
            }
        }

        return new ngOpenGraphBase( $objectAttribute );
    }

    /**
     * Returns data for the attribute
     *
     * @return string
     */
    public function getData()
    {
        return trim( $this->ContentObjectAttribute->attribute( 'data_text' ) );
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
        return $this->getData();
    }

    /**
     * @var eZContentObjectAttribute
     */
    public $ContentObjectAttribute;

    /**
     * @var eZINI
     */
    static $ogIni;
}

ngOpenGraphBase::$ogIni = eZINI::instance( 'ngopengraph.ini' );
