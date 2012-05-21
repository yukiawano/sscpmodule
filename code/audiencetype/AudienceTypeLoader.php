<?php
/**
 * Audience Type Loader
 * @author yuki
 *
 */
class AudienceTypeLoader{
	public function load(){
		// require_once 'thirdparty/zend_translate_railsyaml/library/Translate/Adapter/thirdparty/sfYaml/lib/sfYamlParser.php';
		// $parser = new sfYamlParser();
		// $result = $parser->parse(file_get_contents(SSCP_PATH.'/_config/audiencetype.yml'));
		
		$result = Config::inst()->get("AudienceType", "AudienceTypes");
		var_dump($result);
		return $result;
		// return $result["AudienceType"]["AudienceTypes"];
	}
}