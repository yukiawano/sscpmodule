<?php

require("../Bedrock.php");



class BedrockInsects extends BedrockNode {

	protected $iteratorClass = "BedrockInsects_Iterator";
}


class BedrockInsects_Iterator extends BedrockNode_Iterator {

	protected $iteratorNodeClass = "BedrockInsect";
}


class BedrockInsect extends BedrockNode {

	public function getShouldIKillIt() {		
		return $this->getAnnoying() ? "yes" : "no";
	}
}

$yml = new BedrockYAML("sample.yml");
echo "<h2>Testing YAML load</h2>";
echo ($yml->size() > 0) ? "pass" : "fail";

echo "<h2>Testing traversal to child node</h2>";
echo ($yml->getLivingThings()->getAnimals()->size() > 0) ? "pass" : "fail";

echo "<h2>Testing booleans</h2>";
echo ($yml->getLivingThings()->getAnimals()->getMammals()->getLand()->getDog()->getDomesticated() == true) ? "pass" : "fail";

echo "<h2>Testing sequential array</h2>";
$str = "";
foreach ($yml->getLivingThings()->getPlants()->getAquatic() as $plant) {
	$str .= $plant;
}
echo $str == "SeaweedKelp" ? "pass" : "fail: $str";

echo "<h2>Testing mapped array</h2>";
$str = "";
foreach($yml->getLivingThings() as $thing => $nodes) {
	$str .= $thing;
}
echo $str == "AnimalsPlants" ? "pass" : "fail";

echo "<h2>Testing traversal to parent node</h2>";
echo ($yml->getLivingThings()->getAnimals()->getMammals()->getAquatic()->getWhales()->getParentNode()->getKey() == "Aquatic") ? "pass" : "fail";

echo "<h2>Testing first()</h2>";
echo ($yml->getLivingThings()->getPlants()->getAquatic()->first() == "Seaweed") ? "pass" : "fail";

echo "<h2>Testing last()</h2>";
echo ($yml->getLivingThings()->getPlants()->getLand()->getTrees()->last() == "Maple") ? "pass" : "fail";

echo "<h2>Testing custom node class</h2>";
$pass = false;
foreach($yml->getLivingThings()->getAnimals()->getInsects() as $insect) {
	if($insect->getShouldIKillIt() == "yes") $pass = true;
}
echo $pass ? "pass" : "fail";

echo "<h2>Testing template</h2>";
$template = new BedrockTemplate("template.bedrock");
$template->bind($yml);
//echo $template->debug();
echo $template->render();

