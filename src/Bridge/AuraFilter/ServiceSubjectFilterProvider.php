<?php
declare(strict_types=1);
namespace Fob\DiAuraFilterModule\Bridge\AuraFilter;

use Aura\Filter\FilterFactory;
use Ray\Di\Di\Inject;
use Ray\Di\Di\Named;
use Ray\Di\ProviderInterface;

class ServiceSubjectFilterProvider implements ProviderInterface
{
    /**
     * @var array callable []
     */
    private $filtersByFilterType;

    /**
     * @Inject
     * @Named("config=fob.aura_filter.service_config")
     *
     * @param ConfigProcessor $processor
     * @param array $config 設定
     */
    public function __construct(ConfigProcessor $processor, array $config = [])
    {
        $this->filtersByFilterType = $processor->process($config);
    }

    public function get()
    {
        return (new FilterFactory($this->filtersByFilterType['validate_filters'], $this->filtersByFilterType['sanitize_filters']))->newSubjectFilter();
    }
}
