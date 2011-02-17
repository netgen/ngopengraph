<?php
 
class OpenGraphOperator
{
    function OpenGraphOperator()
    {
        $this->Operators = array( 'opengraph' );
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
       return array( 'opengraph' => array( 'nodeid' => array( 'type' => 'integer',
                                                                'required' => true,
                                                                'default' => 0 ) )
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
			} break;
       }
    }

	function generateOpenGraphTags( $nodeID )
	{
		$returnArray = array();

		$ogIni = eZINI::instance( 'ngopengraph.ini' );
		$facebookCompatible = $ogIni->variable( 'General', 'FacebookCompatible' );
		$availableClasses = $ogIni->variable( 'General', 'Classes' );

		$contentNode = eZContentObjectTreeNode::fetch($nodeID);

		if(!($contentNode instanceof eZContentObjectTreeNode))
		{
			return array();
		}

		$contentObject = $contentNode->object();

		if(!($contentObject instanceof eZContentObject) || !in_array($contentObject->contentClassIdentifier(), $availableClasses))
		{
			return array();
		}

		$returnArray = $this->processGenericData($contentNode, $ogIni, $facebookCompatible, $returnArray);

		$returnArray = $this->processObject($contentObject, $ogIni, $returnArray);
		
		if($this->checkRequirements($returnArray, $facebookCompatible))
			return $returnArray;
		else
			return array();
	}

	function processGenericData($contentNode, $ogIni, $facebookCompatible, $returnArray)
	{
		$siteName = $ogIni->variable( 'GenericData', 'site_name' );
		if(strlen(trim($siteName)) > 0)
			$returnArray['og:site_name'] = trim($siteName);

		$siteUrl = $ogIni->variable( 'GenericData', 'site_url' );
		if(strlen(trim($siteUrl)) > 0)
			if(ereg('/$', $siteUrl))
				$returnArray['og:url'] = $siteUrl . $contentNode->urlAlias();
			else
				$returnArray['og:url'] = $siteUrl . '/' . $contentNode->urlAlias();

		if($facebookCompatible == 'true')
		{
			$appID = $ogIni->variable( 'GenericData', 'app_id' );
			if(strlen(trim($appID)) > 0)
				$returnArray['fb:app_id'] = trim($appID);
	
			$defaultAdmin = $ogIni->variable( 'GenericData', 'default_admin' );
			if(strlen(trim($defaultAdmin)) > 0)
			{
				$data = trim($defaultAdmin);
				
				$admins = $ogIni->variable( 'GenericData', 'admins' );
				
				if(count($admins) > 0)
				{
					$admins = trim(implode(',', $admins));
					$data = $data . ',' . $admins;
				}
			}
			$returnArray['fb:admins'] = $data;
		}
		
		return $returnArray;
	}

	function processObject($contentObject, $ogIni, $returnArray)
	{
		$literalValues = $ogIni->variable( $contentObject->contentClassIdentifier(), 'LiteralMap' );

		foreach($literalValues as $key => $value)
		{
			if(strlen($value) > 0)
				$returnArray[$key] = $value;
		}

		$attributeValues = $ogIni->variableArray( $contentObject->contentClassIdentifier(), 'AttributeMap' );

		foreach($attributeValues as $key => $value)
		{
			$contentObjectAttributeArray = $contentObject->fetchAttributesByIdentifier(array($value[0]));
			$contentObjectAttributeArray = array_values($contentObjectAttributeArray);
			$contentObjectAttribute = $contentObjectAttributeArray[0];

			if($contentObjectAttribute instanceof eZContentObjectAttribute)
			{
				$openGraphHandler = ngOpenGraphBase::getInstance($contentObjectAttribute);
				
				if(count($value) == 1)
					$data = $openGraphHandler->getData();
				else if(count($value) == 2)
					$data = $openGraphHandler->getDataMember($value[1]);
				else
					$data = "";
				
				if(strlen($data) > 0)
					$returnArray[$key] = $data;
			}
		}
		
		return $returnArray;
	}
	
	function checkRequirements($returnArray, $facebookCompatible)
	{
		$arrayKeys = array_keys($returnArray);

		if(!in_array('og:title', $arrayKeys) || !in_array('og:type', $arrayKeys) ||
			!in_array('og:image', $arrayKeys) || !in_array('og:url', $arrayKeys))
		{
			return false;
		}
		
		if($facebookCompatible == 'true')
		{
			if (!in_array('og:site_name', $arrayKeys) || (!in_array('fb:app_id', $arrayKeys) && !in_array('fb:admins', $arrayKeys)))
			{
				return false;
			}
		}
		
		return true;
	}
}
 
?>