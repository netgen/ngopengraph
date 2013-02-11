<?php

class OpenGraphOperator
{
    private $debug = false;

    function OpenGraphOperator()
    {
        $this->Operators = array( 'opengraph', 'language_code' );
    }

    function &operatorList()
    {
        return $this->Operators;
    }

    function namedParameterPerOperator()
    {
        return true;
    }

    function namedParameterList()
    {
        return array(
            'opengraph' => array(
                'nodeid' => array(
                    'type' => 'integer',
                    'required' => true,
                    'default' => 0
                )
            ),
            'language_code' => array()
        );
    }

    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace,
                     &$currentNamespace, &$operatorValue, &$namedParameters )
    {
       switch ( $operatorName )
       {
            case 'opengraph':
            {
                $operatorValue = $this->generateOpenGraphTags( $namedParameters['nodeid'] );
                break;
            }
            case 'language_code':
            {
                $operatorValue = eZLocale::instance()->httpLocaleCode();
                break;
            }
       }
    }

    function generateOpenGraphTags( $nodeID )
    {
        $returnArray = array();

        $ogIni = eZINI::instance( 'ngopengraph.ini' );
        $facebookCompatible = $ogIni->variable( 'General', 'FacebookCompatible' );
        $availableClasses = $ogIni->variable( 'General', 'Classes' );
        $this->debug = $ogIni->variable( 'General', 'Debug' ) == 'enabled';

        $contentNode = eZContentObjectTreeNode::fetch( $nodeID );

        if ( !$contentNode instanceof eZContentObjectTreeNode )
        {
            return array();
        }

        $contentObject = $contentNode->object();

        if ( !$contentObject instanceof eZContentObject || !in_array( $contentObject->contentClassIdentifier(), $availableClasses ) )
        {
            return array();
        }

        $returnArray = $this->processGenericData( $contentNode, $ogIni, $facebookCompatible, $returnArray );

        $returnArray = $this->processObject( $contentObject, $ogIni, $returnArray );

        if ( $this->checkRequirements( $returnArray, $facebookCompatible ) )
        {
            return $returnArray;
        }
        else
        {
            if ( $this->debug )
            {
                eZDebug::writeDebug( 'No', 'Facebook Compatible?' );
            }

            return array();
        }
    }

    function processGenericData( $contentNode, $ogIni, $facebookCompatible, $returnArray )
    {
        $siteName = trim( eZINI::instance()->variable( 'SiteSettings', 'SiteName' ) );
        if ( !empty( $siteName ) )
        {
            $returnArray['og:site_name'] = $siteName;
        }

        $urlAlias = $contentNode->urlAlias();
        eZURI::transformURI( $urlAlias, false, 'full' );
        $returnArray['og:url'] = $urlAlias;

        if ( $facebookCompatible == 'true' )
        {
            $appID = trim( $ogIni->variable( 'GenericData', 'app_id' ) );
            if ( !empty( $appID ) )
            {
                $returnArray['fb:app_id'] = $appID;
            }

            $defaultAdmin = trim( $ogIni->variable( 'GenericData', 'default_admin' ) );
            $data = '';
            if ( !empty( $defaultAdmin ) )
            {
                $data = $defaultAdmin;

                $admins = $ogIni->variable( 'GenericData', 'admins' );

                if ( !empty( $admins ) )
                {
                    $admins = trim( implode( ',', $admins ) );
                    $data = $data . ',' . $admins;
                }
            }

            if ( !empty( $data ) )
            {
                $returnArray['fb:admins'] = $data;
            }
        }

        return $returnArray;
    }

    function processObject( $contentObject, $ogIni, $returnArray )
    {
        if ( $ogIni->hasVariable( $contentObject->contentClassIdentifier(), 'LiteralMap' ) )
        {
            $literalValues = $ogIni->variable( $contentObject->contentClassIdentifier(), 'LiteralMap' );
            if ( $this->debug )
            {
                eZDebug::writeDebug( $literalValues, 'LiteralMap' );
            }

            if ( $literalValues )
            {
                foreach ( $literalValues as $key => $value )
                {
                    if ( !empty( $value ) )
                    {
                        $returnArray[$key] = $value;
                    }
                }
            }
        }

        if ( $ogIni->hasVariable( $contentObject->contentClassIdentifier(), 'AttributeMap' ) )
        {
            $attributeValues = $ogIni->variableArray( $contentObject->contentClassIdentifier(), 'AttributeMap' );
            if ( $this->debug )
            {
                eZDebug::writeDebug( $attributeValues, 'AttributeMap' );
            }

            if ( $attributeValues )
            {
                foreach ( $attributeValues as $key => $value )
                {
                    $contentObjectAttributeArray = $contentObject->fetchAttributesByIdentifier( array( $value[0] ) );
                    if ( !is_array( $contentObjectAttributeArray ) )
                    {
                        continue;
                    }

                    $contentObjectAttributeArray = array_values( $contentObjectAttributeArray );
                    $contentObjectAttribute = $contentObjectAttributeArray[0];

                    if ( $contentObjectAttribute instanceof eZContentObjectAttribute )
                    {
                        $openGraphHandler = ngOpenGraphBase::getInstance( $contentObjectAttribute );

                        if ( count( $value ) == 1 )
                        {
                            $data = $openGraphHandler->getData();
                        }
                        else if ( count( $value ) == 2 )
                        {
                            $data = $openGraphHandler->getDataMember( $value[1] );
                        }
                        else
                        {
                            $data = "";
                        }

                        if ( is_array( $data ) || !empty( $data ) )
                        {
                            $returnArray[$key] = $data;
                        }
                    }
                }
            }
        }

        return $returnArray;
    }

    function checkRequirements( $returnArray, $facebookCompatible )
    {
        $arrayKeys = array_keys( $returnArray );

        if ( !in_array( 'og:title', $arrayKeys ) || !in_array( 'og:type', $arrayKeys ) ||
            !in_array( 'og:image', $arrayKeys ) || !in_array( 'og:url', $arrayKeys ) )
        {
            if ( $this->debug )
            {
                eZDebug::writeError( $arrayKeys, 'Missing an OG required field: title, image, type, or url' );
            }

            return false;
        }

        if ( $facebookCompatible == 'true' )
        {
            if ( !in_array( 'og:site_name', $arrayKeys ) || ( !in_array( 'fb:app_id', $arrayKeys ) && !in_array( 'fb:admins', $arrayKeys ) ) )
            {
                if ( $this->debug )
                {
                    eZDebug::writeError( $arrayKeys, 'Missing a FB required field (in ngopengraph.ini): app_id, DefaultAdmin, or Sitename (site.ini)' );
                }

                return false;
            }
        }

        return true;
    }
}
