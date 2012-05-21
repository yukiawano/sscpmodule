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
		// $result = $parser->parse(file_get_contents('../../_config/audiencetype.yml'));
		$result = Config::inst()->get("audiencetype", "AudienceTypes");
		var_dump($result);
		return $result;
	}
}