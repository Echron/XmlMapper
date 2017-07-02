<?php

class MapperTest extends \PHPUnit\Framework\TestCase
{
    public function testBasics()
    {

        //Definitions
        $level1 = new \Echron\XmlMapper\Definition\Base('Level1');

        $fieldA = new \Echron\XmlMapper\Definition\Base('FieldA');
        $level1->addChild($fieldA);

        $fieldB = new \Echron\XmlMapper\Definition\Base('FieldB');
        $level1->addChild($fieldB);

        $mapper = new \Echron\XmlMapper\Mapper($level1);

        $data = $mapper->process(file_get_contents(realpath(__DIR__) . '/assets/test1.xml'));

        $this->assertEqualsToFile($data, realpath(__DIR__) . '/assets/test1.json');

    }

    private function assertEqualsToFile($data, $jsonFile)
    {
        //$jsonData = json_encode($data, JSON_PRETTY_PRINT);

        $expectedJsonData = file_get_contents($jsonFile);
        $expectedJson = json_decode($expectedJsonData, true);

        // $expectedJsonString = json_encode($expectedJson, JSON_PRETTY_PRINT);
        $this->assertEquals($expectedJson, $data);
    }
}