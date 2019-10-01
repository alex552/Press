<?php


namespace alex552\Press\Tests;


use alex552\Press\MarkdownParser;
use Orchestra\Testbench\TestCase;

class MarkdownTest extends TestCase
{
    /** @test */
    public function simple_markdown_is_parsed(){
        $this->assertEquals("<h1>test</h1>",MarkdownParser::parse('# test'));
    }
}