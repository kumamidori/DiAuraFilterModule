<?php
declare(strict_types=1);
namespace Fob\DiAuraFilterModule\Bridge\AuraFilter;

use Fob\DiAuraFilterModule\Di\InjectorAwareInterface;
use Ray\Di\InjectorInterface;

class ConfigProcessor implements InjectorAwareInterface
{
    /**
     * @var array
     */
    private $injector;

    /**
     * @param InjectorInterface $injector
     */
    public function __construct(InjectorInterface $injector)
    {
        $this->injector = $injector;
    }

    public function process(array $config)
    {
        $filtersByType = [];
        foreach ($config as $type => $typeConfs) {
            if ($typeConfs === null || $typeConfs === []) {
                continue;
            }
            foreach ($typeConfs as $ruleName => $conf) {
                if ($this->isServiceCallback($conf)) {
                    $class = $conf['callback'][0];
                    $method = $conf['callback'][1];

                    $service = $this->injector->getInstance($class);
                    /** @var ServiceCallbackAuraValidate $callbackValidator */
                    $callbackValidator = $this->injector->getInstance(ServiceCallbackAuraValidate::class);
                    $callbackValidator->setCallable([$service, $method]);

                    $filtersByType[$type][$ruleName] = function () use ($callbackValidator) {
                        return $callbackValidator;
                    };
                } else {
                    $injector = $this->injector;
                    $filtersByType[$type][$ruleName] = function () use ($conf, $injector) {
                        return $this->injector->getInstance($conf['class']);
                    };
                }
            }
        }
        if (! isset($filtersByType['validate_filters'])) {
            $filtersByType['validate_filters'] = [];
        }
        if (! isset($filtersByType['sanitize_filters'])) {
            $filtersByType['sanitize_filters'] = [];
        }

        return $filtersByType;
    }

    /**
     * @return bool
     */
    private function isServiceCallback(array $conf)
    {
        return isset($conf['type']) && $conf['type'] === 'service_callback';
    }
}
