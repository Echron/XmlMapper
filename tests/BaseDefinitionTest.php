<?php

class BaseDefinitionTest extends \PHPUnit\Framework\TestCase
{
    public function testParent()
    {
        $root = new \Echron\XmlMapper\Definition\Base('root');
        $level1 = new \Echron\XmlMapper\Definition\Base('level1');
        $root->addChild($level1);


        $this->assertTrue($level1->hasParent());
        $this->assertEquals('root',$level1->getParent()->getName());


    }
}