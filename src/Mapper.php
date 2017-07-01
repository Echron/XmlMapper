<?php

namespace Echron\XmlMapper;

use Echron\XmlMapper\Definition\Base;

class Mapper
{
    private $definition;

    private $parser;

    private $currentDefinition;

    private $data;

    private $level = 0;

    private $parentData;

    private $currentContent = null;

    public function __construct(Base $definition)
    {

        $this->definition = $definition;
        $this->currentDefinition = $definition;

        $this->data = [];
    }

    public function process(string $xmlString)
    {
        $this->parser = xml_parser_create();
        xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, false);
        xml_parser_set_option($this->parser, XML_OPTION_SKIP_WHITE, true);
        xml_set_character_data_handler($this->parser, [
            $this,
            'readData',
        ]);
        xml_set_element_handler($this->parser, [
            $this,
            "openTag",
        ], [
            $this,
            "closeTag",
        ]);
        // while ($data = fread($file, 4096)) {
        if (!xml_parse($this->parser, $xmlString, true)) {
            throw new \Exception(sprintf("XML error: %s at line %d", xml_error_string(xml_get_error_code($this->parser)), xml_get_current_line_number($this->parser)));
        }

        xml_parser_free($this->parser);

        return $this->data;
        // }
    }

    protected function readData($parser, $data)
    {

//        echo $this->currentDefinition->getName() . PHP_EOL;
//        var_dump($data);
        $this->currentContent = $data;
    }

    protected function openTag($parser, $nodeName, $attrs)
    {
        if ($this->currentDefinition->hasChild($nodeName)) {

            $this->level++;

            echo str_repeat('{', $this->level) . ' ' . $nodeName . PHP_EOL;

            $currentData = [];
            if (isset($this->data[$nodeName])) {
                $currentData = $this->data[$nodeName];
            }

            $this->parentData = $this->data;

            $this->data = $currentData;

            $definition = $this->currentDefinition->getChild($nodeName);

            $this->currentDefinition = $definition;

            //   var_dump($this->data);

        } else {
            echo 'Skip ' . $nodeName . PHP_EOL;
        }

        //   var_dump($attrs);
//        $this->env[] = strtolower($nodeName);
//        if (is_callable([
//            $this,
//            $callback = "openTag_" . implode("_", $this->env),
//        ])) {
//            $this->$callback($attrs);
//        }
    }

    protected function closeTag($parser, string $nodeName)
    {
        if ($this->currentDefinition->getName() === $nodeName) {

            //   echo str_repeat('}} ', $this->level) . ' ' . $nodeName . PHP_EOL;
            $this->level--;

            // echo 'X:' . $this->currentContent . PHP_EOL;

            // echo str_repeat('} ', $this->level) . ' ' . $nodeName . PHP_EOL;

            //    if (!is_null($this->currentContent)) {
            $this->data[$this->currentDefinition->getName()] = $this->currentContent;
            $this->currentContent = null;
            //      }

            //Reset current defnition

            $hasParent = $this->currentDefinition->hasParent();
            if ($hasParent) {
                $this->currentDefinition = $this->currentDefinition->getParent();

            }
            if (isset($this->parentData[$this->currentDefinition->getName()])) {
                $data = $this->parentData[$this->currentDefinition->getName()];

                $this->data = array_merge($data, $this->data);
            }

            if ($hasParent) {
                $this->parentData[$this->currentDefinition->getName()] = $this->data;
            }

            $this->data = $this->parentData;

        }

    }
}