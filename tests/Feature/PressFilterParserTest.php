<?php


namespace alex552\Press\Tests;


use alex552\Press\PressFileParser;
use Carbon\Carbon;
use Orchestra\Testbench\TestCase;

class PressFilterParserTest extends TestCase
{
    /** @test */
    public function the_head_and_body_gets_split()
    {
        $pressFileParser = (new PressFileParser(__DIR__.'/../blog/markdown.md'));
        $data = $pressFileParser->getRawData();
        $this->assertStringContainsStringIgnoringCase('title: title', $data[1]);
        $this->assertStringContainsStringIgnoringCase('description: description', $data[1]);
        $this->assertStringContainsStringIgnoringCase('body text', $data[2]);
    }

    /** @test */
    public function a_string_can_also_be_used_instead()
    {
        $pressFileParser = (new PressFileParser("---\ntitle: My Title\n---\nBlog post body here"));
        $data = $pressFileParser->getRawData();
        $this->assertStringContainsStringIgnoringCase('title: My Title', $data[1]);
        $this->assertStringContainsStringIgnoringCase('Blog post body here', $data[2]);
    }
    /** @test */
    public function each_head_field_gets_separated()
    {
        $pressFileParser = (new PressFileParser(__DIR__.'/../blog/markdown.md'));
        $data = $pressFileParser->getData();
        $this->assertEquals('title', $data['title']);
        $this->assertEquals('description', $data['description']);
    }
    /** @test */
    public function the_body_gets_saved_and_trimmed()
    {
        $pressFileParser = (new PressFileParser(__DIR__.'/../blog/markdown.md'));
        $data = $pressFileParser->getData();
        $this->assertEquals("<h1>Heading</h1>\n<p>body text</p>", $data['body']);
    }
    /** @test */
    public function a_date_field_gets_parsed()
    {
        $pressFileParser = (new PressFileParser("---\ndate: May 14, 1988\n---\n"));
        $data = $pressFileParser->getData();
        $this->assertInstanceOf(Carbon::class, $data['date']);
        $this->assertEquals('05/14/1988', $data['date']->format('m/d/Y'));
    }
}