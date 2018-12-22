<?php
declare(strict_types=1);
namespace Fob\DiAuraFilterModule\Bridge\AuraFilter;

use Aura\Filter\FilterFactory;
use Fob\DiAuraFilterModule\Di\InjectorAwareInterface;
use Ray\Di\Di\Inject;
use Ray\Di\Di\Named;
use Ray\Di\ProviderInterface;

class ServiceFilterFactoryProvider implements ProviderInterface, InjectorAwareInterface
{
    /**
     * @var array callable []
     */
    private $filtersByType;

    /**
     * @Inject
     * @Named("config=fob.aura_filter.filter.service_config")
     *
     * @param ConfigProcessor $processor
     * @param array $config 設定
     */
    public function __construct(ConfigProcessor $processor, array $config = [])
    {
        $this->filtersByType = $processor->process($config);
    }

    public function get()
    {
        return new FilterFactory($this->filtersByType['validate_filters'], $this->filtersByType['sanitize_filters']);
    }
}
