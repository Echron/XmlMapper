<?php

class MapperTest extends \PHPUnit\Framework\TestCase
{
    public function testBasics()
    {

        //Definitions
        $artikel = new \Echron\XmlMapper\Definition\Base('Artikel');

        $artCode = new \Echron\XmlMapper\Definition\Base('ArtCode');
        $artikel->addChild($artCode);

        $artCode = new \Echron\XmlMapper\Definition\Base('ArtNummer');
        $artikel->addChild($artCode);

        $artCode = new \Echron\XmlMapper\Definition\Base('VolgNummer');
        $artikel->addChild($artCode);

        $artCode = new \Echron\XmlMapper\Definition\Base('Groep');
        $artikel->addChild($artCode);

        $artCode = new \Echron\XmlMapper\Definition\Base('Soort');
        $artikel->addChild($artCode);

        $mapper = new \Echron\XmlMapper\Mapper($artikel);

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