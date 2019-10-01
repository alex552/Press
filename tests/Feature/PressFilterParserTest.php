<?php


namespace alex552\Press\Tests;


use alex552\Press\PressFileParser;
use Orchestra\Testbench\TestCase;

class PressFilterParserTest extends TestCase
{
    /** @test */
    public function head_and_body_split(){
        $pressFileParser = (new PressFileParser(__DIR__.'/../blog/markdown.md'));
        $data = $pressFileParser->getData();
        $this->assertContains("title: title",$data[1]);
        $this->assertContains("description: description",$data[1]);
        $this->assertContains("body text",$data[2]);
    }
}