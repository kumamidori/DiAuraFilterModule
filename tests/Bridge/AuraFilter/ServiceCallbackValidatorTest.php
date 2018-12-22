<?php
declare(strict_types=1);
namespace Fob\DiAuraFilterModule\Bridge\AuraFilter;

use PHPUnit\Framework\TestCase;

class FooSpec
{
    public function isSatisfiedBy(\stdClass $subject, $field)
    {
        return true;
    }
}

class ServiceCallbackValidatorTest extends TestCase
{
    private $callbackValidator;

    protected function setUp()
    {
        parent::setUp();
        $this->callbackValidator = new ServiceCallbackAuraValidate();
    }

    public function testSetService()
    {
        $service = new FooSpec();
        $this->callbackValidator->setCallable([$service, 'isSatisfiedBy']);
        $fixture = new \stdClass();
        $fixture->value = 'foo';
        $this->assertTrue(call_user_func($this->callbackValidator, $fixture, 'value'));
    }
}
