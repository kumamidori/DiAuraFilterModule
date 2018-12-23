<?php
declare(strict_types=1);
namespace Fob\DiAuraFilterModule\Module;

use FormalBears\Foundation\Config\Definition\AbstractConfiguration;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

class DiAuraFilterConfiguration extends AbstractConfiguration
{
    /**
     * @return string
     */
    public function getNamespace(): string
    {
        return 'di_aura_filter';
    }

    /**
     * {@inheritdoc}
     */
    protected function defineChildren(NodeBuilder $nodeBuilder): NodeBuilder
    {
        $nodeBuilder
            ->arrayNode('validate_filters')
                ->useAttributeAsKey('rule_name')
                ->prototype('array')
                    ->children()
                        ->scalarNode('class')
                            ->info('class（バリデータ直接指定） と callback （サービスクラスのコールバック指定）は二者択一')
                        ->end()
                        ->scalarNode('type')->end()
                        ->scalarNode('callback')->end()
                    ->end()
                ->end()
            ->end()
            ->arrayNode('sanitize_filters')
                ->useAttributeAsKey('rule_name')
                    ->prototype('array')
                    ->children()
                        ->scalarNode('class')
                            ->info('class（バリデータ直接指定） と callback （サービスコールバック指定）は二者択一')
                        ->end()
                        ->scalarNode('type')->end()
                        ->scalarNode('callback')->end()
                    ->end()
                ->end()
            ->end()
        ->end();

        return $nodeBuilder;
    }
}
