<?php
declare(strict_types=1);
namespace Fob\DiAuraFilterModule\Bridge\AuraFilter;

use PHPUnit\Framework\TestCase;
use Ray\Di\AbstractModule;
use Ray\Di\Injector;

class AuraFilterStubModule extends AbstractModule
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->bind(FakeFooValidator::class);
    }
}

class FakeFooValidator {
    public function __invoke($subject, $field)
    {
        return true;
    }
}
class FakeFooSanitizer {
    public function __invoke($subject, $field)
    {
        return 'abc';
    }
}

class ConfigProcessorTest extends TestCase
{
    /**
     * @var ConfigProcessor
     */
    private $processor;

    protected function setUp()
    {
        parent::setUp();
        $injector = new Injector(new AuraFilterStubModule);
        $this->processor = new ConfigProcessor($injector);
    }

    public function testNew()
    {
        $this->assertInstanceOf(ConfigProcessor::class, $this->processor);
    }

    public function testCaseEmptyConfig()
    {
        $result = $this->processor->process([]);
        $this->assertArrayHasKey('validate_filters', $result);
        $this->assertArrayHasKey('sanitize_filters', $result);
        $this->assertEquals([], $result['validate_filters']);
        $this->assertEquals([], $result['sanitize_filters']);
    }

    public function testCaseValidateOnlyExists()
    {
        $ruleName = 'foo.bar';
        $fixture = [
            'validate_filters' => [
                $ruleName => ['class' => FakeFooValidator::class],
            ],
        ];
        $processed = $this->processor->process($fixture);
        $fooClosure = $processed['validate_filters'][$ruleName];
        $fooInstance = call_user_func($fooClosure);
        $fooExecuted = $fooInstance(['dummy' => 'abc'], 'abc');

        $this->assertArrayHasKey('validate_filters', $processed);
        $this->assertArrayHasKey('sanitize_filters', $processed);
        $this->assertInstanceOf(FakeFooValidator::class, $fooInstance);
        $this->assertTrue($fooExecuted);
        $this->assertArrayHasKey('sanitize_filters', $processed);
        $this->assertEquals([], $processed['sanitize_filters']);
    }

    public function testCaseSanitizeOnlyExists()
    {
        $ruleName = 'foo.bar';
        $fixture = [
            'sanitize_filters' => [
                $ruleName => ['class' => FakeFooSanitizer::class],
            ],
        ];
        $processed = $this->processor->process($fixture);
        $fooClosure = $processed['sanitize_filters'][$ruleName];
        $fooInstance = call_user_func($fooClosure);
        $fooExecuted = $fooInstance(['dummy' => 'abc'], 'abc');

        $this->assertArrayHasKey('validate_filters', $processed);
        $this->assertArrayHasKey('sanitize_filters', $processed);
        $this->assertInstanceOf(FakeFooSanitizer::class, $fooInstance);
        $this->assertSame('abc', $fooExecuted);
        $this->assertEquals([], $processed['validate_filters']);
    }
}
