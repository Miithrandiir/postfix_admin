<?php

declare(strict_types=1);

namespace App\Services;

use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ViteAssetExtension extends AbstractExtension
{
    private const CACHE_KEY = 'je_l_appelle_comme_je_veux';
    private string $manifest;
    private CacheItemPoolInterface $cache;
    private RequestStack $requestStack;
    private KernelInterface $kernel;
    private ?array $manifestData = null;

    public function __construct(string $manifest, CacheItemPoolInterface $cacheItemPool, RequestStack $requestStack, KernelInterface $kernel)
    {
        $this->manifest = $manifest;
        $this->cache = $cacheItemPool;
        $this->requestStack = $requestStack;
        $this->kernel = $kernel;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('vite_asset', [$this, 'asset'], ['is_safe' => ['html']]),
        ];
    }

    public function asset(string $entry, array $deps = []): string
    {
        if ($this->kernel->isDebug()) {
            return $this->assetDev($entry, $deps);
        }

        return $this->assetProd($entry);
    }

    public function assetDev(string $entry, array $deps): string
    {
        $html = <<<'HTML'
            <script type="module" src="/build/@vite/client"></script>
            HTML;
        if (\in_array('react', $deps, true)) {
            $html .= '<script type="module">
                import RefreshRuntime from "/build/@react-refresh"
    RefreshRuntime.injectIntoGlobalHook(window)
    window.$RefreshReg$ = () => {}
    window.$RefreshSig$ = () => (type) => type
    window.__vite_plugin_react_preamble_installed__ = true
        </script>';
        }
        $html .= <<<HTML
            <script type="module" src="/build/{$entry}" defer></script>
            HTML;
        $host = $this->requestStack->getCurrentRequest()?->getHost();

        return $host === null ? $html : str_replace('localhost', $host, $html);
    }

    public function assetProd(string $entry): string
    {
        if ($this->manifestData === null) {
            $item = $this->cache->getItem(self::CACHE_KEY);
            if ($item->isHit()) {
                $this->manifestData = $item->get();
            } else {
                $this->manifestData = json_decode((string)file_get_contents($this->manifest), true);
                $item->set($this->manifestData);
                $this->cache->save($item);
            }
        }
        $file = $this->manifestData[$entry]['file'];
        $css = $this->manifestData[$entry]['css'] ?? [];
        $imports = $this->manifestData[$entry]['imports'] ?? [];
        $html = <<<HTML
            <script type="module" src="/build/{$file}" defer></script>
            HTML;
        foreach ($css as $cssFile) {
            $html .= <<<HTML
                <link rel="stylesheet" media="screen" href="/build/{$cssFile}"/>
                HTML;
        }

        foreach ($imports as $import) {
            $html .= <<<HTML
                <link rel="modulepreload" href="/build/{$import}"/>
                HTML;
        }

        return $html;
    }
}
