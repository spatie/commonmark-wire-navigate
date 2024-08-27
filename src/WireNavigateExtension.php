<?php

namespace Spatie\CommonMarkWireNavigate;

use Exception;
use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Extension\CommonMark\Renderer\Inline\LinkRenderer;
use League\CommonMark\Extension\ConfigurableExtensionInterface;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\Config\ConfigurationAwareInterface;
use League\Config\ConfigurationBuilderInterface;
use League\Config\ConfigurationInterface;
use Nette\Schema\Expect;
use Stringable;

class WireNavigateExtension implements ConfigurableExtensionInterface, ConfigurationAwareInterface, NodeRendererInterface
{
    protected ShouldWireNavigate $shouldWireNavigate;

    protected string $attribute;

    protected ConfigurationInterface $configuration;

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addRenderer(Link::class, $this, 10);
    }

    public function render(Node $node, ChildNodeRendererInterface $childRenderer): Stringable|string|null
    {
        if (! $node instanceof Link) {
            throw new Exception('Unsupported node');
        }

        $this->shouldWireNavigate ??= new ShouldWireNavigate(
            domain: $this->configuration->get('wire_navigate/domain'),
            paths: $this->configuration->get('wire_navigate/paths'),
            fragment: $this->configuration->get('wire_navigate/fragment'),
        );

        $this->attribute ??= $this->configuration->get('wire_navigate/hover')
            ? 'wire:navigate.hover'
            : 'wire:navigate';

        if (($this->shouldWireNavigate)($node->getUrl())) {
            $node->data->set(
                'attributes',
                array_merge($node->data->get('attributes'), [
                    $this->attribute => true,
                ]),
            );
        }

        $renderer = new LinkRenderer;
        $renderer->setConfiguration($this->configuration);

        return $renderer->render($node, $childRenderer);
    }

    public function setConfiguration(ConfigurationInterface $configuration): void
    {
        $this->configuration = $configuration;
    }

    public function configureSchema(ConfigurationBuilderInterface $builder): void
    {
        $builder->addSchema('wire_navigate', Expect::structure([
            'domain' => Expect::string(''),
            'paths' => Expect::anyOf(Expect::null(), Expect::arrayOf(Expect::string()))->default(null),
            'hover' => Expect::bool(false),
            'fragment' => Expect::bool(false),
        ]));
    }
}
