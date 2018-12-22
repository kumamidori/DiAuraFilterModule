<?php
declare(strict_types=1);
namespace Fob\DiAuraFilterModule\Bridge\AuraFilter;

class ServiceCallbackAuraValidate implements AuraValidateInterface
{
    /**
     * @var callable
     */
    private $callable;

    public function setCallable(callable $callable)
    {
        $this->callable = $callable;
    }
    
    public function __invoke(\stdClass $subject, $field)
    {
        if (! $this->callable) {
            throw new \LogicException();
        }

        return call_user_func($this->callable, $subject, $field);
    }
}
