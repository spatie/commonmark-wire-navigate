<?php

namespace Spatie\CommonMarkWireNavigate;

use Closure;
use Exception;
use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Extension\CommonMark\Renderer\Inline\LinkRenderer;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\Config\ConfigurationAwareInterface;
use League\Config\ConfigurationInterface;

class WireNavigateExtension implements ConfigurationAwareInterface, ExtensionInterface, NodeRendererInterface
{
    protected Closure $shouldWireNavigate;

    protected string $attribute;

    protected ConfigurationInterface $configuration;

    public function __construct(
        string $baseUrl = '',
        Closure|array|null $enabled = null,
        bool $hover = false,
    ) {
        $this->shouldWireNavigate = ShouldWireNavigate::from($baseUrl, $enabled);
        $this->attribute = $hover ? 'wire:navigate.hover' : 'wire:navigate';
    }

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addRenderer(Link::class, $this, 10);
    }

    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        if (! $node instanceof Link) {
            throw new Exception('Unsupported node');
        }

        if (($this->shouldWireNavigate)($node->getUrl())) {
            $node->data->set(
                'attributes',
                array_merge($node->data->get('attributes'), [
                    $this->attribute => true,
                ]),
            );
        }

        $renderer = new LinkRenderer();
        $renderer->setConfiguration($this->configuration);

        return $renderer->render($node, $childRenderer);
    }

    public function setConfiguration(ConfigurationInterface $configuration): void
    {
        $this->configuration = $configuration;
    }
}
