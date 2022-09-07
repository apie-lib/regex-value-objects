<?php
namespace Apie\Tests\RegexValueObjects;

use Apie\Fixtures\TestHelpers\TestWithFaker;
use Apie\Fixtures\TestHelpers\TestWithOpenapiSchema;
use Apie\RegexValueObjects\Exceptions\InvalidPhpRegularExpression;
use Apie\RegexValueObjects\PhpRegularExpression;
use PHPUnit\Framework\TestCase;

class PhpRegularExpressionTest extends TestCase
{
    use TestWithFaker;
    use TestWithOpenapiSchema;

    /**
     * @test
     * @dataProvider inputProvider
     */
    public function it_allows_valid_regular_expressions(string $expected, string $expectedDelimiter, string $expectedModifier, string $input)
    {
        $testItem = new PhpRegularExpression($input);
        $this->assertEquals($expectedDelimiter, $testItem->getDelimiter());
        $this->assertEquals($expectedModifier, $testItem->getModifiers());
        $this->assertEquals($expected, $testItem->toNative());
    }

    public function inputProvider()
    {
        yield ['/test/i', '/', 'i', '/test/i'];
        yield ['/test/', '/', '', '/test/'];
    }

    /**
     * @test
     * @dataProvider invalidProvider
     */
    public function it_refuses_invalid_regular_expressions(string $input)
    {
        $this->expectException(InvalidPhpRegularExpression::class);
        new PhpRegularExpression($input);
    }

    /**
     * @test
     * @dataProvider invalidProvider
     */
    public function it_refuses_invalid_regular_expressions_with_fromNative(string $input)
    {
        $this->expectException(InvalidPhpRegularExpression::class);
        PhpRegularExpression::fromNative($input);
    }

    public function invalidProvider()
    {
        yield [''];
        //yield ['[a-z]'];
        yield ["/[a-z]"];
        yield ['/[a-z]/0'];
    }

    /**
     * @test
     */
    public function it_works_with_schema_generator()
    {
        $this->runOpenapiSchemaTestForCreation(
            PhpRegularExpression::class,
            'PhpRegularExpression-post',
            [
                'type' => 'string',
                'format' => 'phpregularexpression'
            ]
        );
    }

    /**
     * @test
     */
    public function it_works_with_apie_faker()
    {
        $this->runFakerTest(PhpRegularExpression::class);
    }
}
