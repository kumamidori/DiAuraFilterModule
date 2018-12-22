<?php
declare(strict_types=1);
namespace Fob\DiAuraFilterModule\Module;

use Aura\Filter\FilterFactory;
use Aura\Filter\SubjectFilter;
use Aura\Filter\ValueFilter;
use Fob\DiAuraFilterModule\Bridge\AuraFilter\ConfigProcessor;
use Fob\DiAuraFilterModule\Bridge\AuraFilter\ServiceFilterFactoryProvider;
use Fob\DiAuraFilterModule\Bridge\AuraFilter\ServiceSubjectFilterProvider;
use Fob\DiAuraFilterModule\Bridge\AuraFilter\ServiceValueFilterProvider;
use FormalBears\Foundation\Config\Module\AbstractConfigAwareModule;

class DiAuraFilterModule extends AbstractConfigAwareModule
{
    /**
     * Configure binding
     */
    public function configure()
    {
        $this->bind(ConfigProcessor::class);
        $this->bind()->annotatedWith('fob.aura_filter.service_config')->toInstance($this->config);
        $this->bind(FilterFactory::class)->annotatedWith('fob.aura_filter.service_filter_factory')->toProvider(ServiceFilterFactoryProvider::class);
        $this->bind(SubjectFilter::class)->annotatedWith('fob.aura_filter.service_subject_filter')->toProvider(ServiceSubjectFilterProvider::class);
        $this->bind(ValueFilter::class)->annotatedWith('fob.aura_filter.service_value_filter')->toProvider(ServiceValueFilterProvider::class);
    }

    protected function getConfiguration()
    {
        return new DiAuraFilterConfiguration();
    }
}
