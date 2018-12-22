<?php
declare(strict_types=1);
namespace Fob\DiAuraFilterModule\Module;

use Aura\Filter\FilterFactory;
use Aura\Filter\SubjectFilter;
use Aura\Filter\ValueFilter;
use FormalBears\Foundation\Config\Process\Registry;
use PHPUnit\Framework\TestCase;
use Ray\Di\Injector;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class DiAuraFilterModuleTest extends TestCase
{
    public function testNew()
    {
        $mock = $this->prophesize(ParameterBag::class);
        $registry = new Registry($mock->reveal());
        $module = new DiAuraFilterModule($registry);
        $this->assertInstanceOf(DiAuraFilterModule::class, $module);
    }

    public function testModuleBindFiltersCaseEmptyConfig()
    {
        $mock = $this->prophesize(ParameterBag::class);
        $registry = new Registry($mock->reveal());
        $module = new DiAuraFilterModule($registry);
        $injector = (new Injector($module));
        $factory = $injector->getInstance(FilterFactory::class, 'fob.aura_filter.service_filter_factory');
        $sFilter = $injector->getInstance(SubjectFilter::class, 'fob.aura_filter.service_subject_filter');
        $vFilter = $injector->getInstance(ValueFilter::class, 'fob.aura_filter.service_value_filter');
        $this->assertInstanceOf(FilterFactory::class, $factory);
        $this->assertInstanceOf(SubjectFilter::class, $sFilter);
        $this->assertInstanceOf(ValueFilter::class, $vFilter);
    }

    public function testModuleBindFactoryCanCreateServiceFilters()
    {
        $mock = $this->prophesize(ParameterBag::class);
        $registry = new Registry($mock->reveal());
        $module = new DiAuraFilterModule($registry);
        $injector = (new Injector($module));
        /** @var FilterFactory $factory */
        $factory = $injector->getInstance(FilterFactory::class, 'fob.aura_filter.service_filter_factory');
        $sFilterByFactory = $factory->newSubjectFilter();
        $sFilterByBind = $injector->getInstance(SubjectFilter::class, 'fob.aura_filter.service_subject_filter');
        $this->assertEquals($sFilterByFactory, $sFilterByBind);
    }
}
