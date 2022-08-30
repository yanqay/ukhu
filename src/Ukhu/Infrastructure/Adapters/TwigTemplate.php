<?php

namespace App\Ukhu\Infrastructure\Adapters;

use App\Ukhu\Domain\Exceptions\InternalError;
use App\Ukhu\Application\Ports\TemplateInterface;

class TwigTemplate implements TemplateInterface
{
    private $url;
    private $debug;
    private $templateLocations;
    private $cache;
    private $cacheLocation;
    private $manifestFile;
    private $publicDir;
    private \Twig\Environment $instance;

    public function __construct(
        string $url,
        bool $debug,
        $templateLocations,
        bool $cache,
        string $cacheLocation,
        string $manifestFile,
        string $publicDir
    ) {
        $this->url = $url;
        $this->debug = $debug;
        $this->templateLocations = $templateLocations;
        $this->cache = $cache;
        $this->cacheLocation = $cacheLocation;
        $this->manifestFile = $manifestFile;
        $this->publicDir = $publicDir;
        $this->initialize();
    }

    public function initialize()
    {
        $loader = new \Twig\Loader\FilesystemLoader($this->templateLocations);

        $twig = new \Twig\Environment($loader, [
            'debug' => $this->debug,
            'cache' => $this->cache ? $this->cacheLocation : false,
            //'debug' => env('APP_DEBUG')? true : false,
            //'cache' => env('APP_DEBUG')? false : $this->cacheLocation,
            // TODO  NOT WORKING
            // 'auto_reload' => env('APP_DEBUG')? true : false,
        ]);

        $twig->addGlobal('app_url', $this->url);

        // extension to use dump()
        $twig->addExtension(new \Twig\Extension\DebugExtension);

        // extension to inject webpack entries to twig templates
        $twig->addExtension(new \Fullpipe\TwigWebpackExtension\WebpackExtension(
            $this->manifestFile,
            $this->publicDir
        ));

        // custom twig function: year
        $yearFunction = new \Twig\TwigFunction('year', function () {
            return date('Y');
        });
        $twig->addFunction($yearFunction);

        // custom twig function: url, asset
        $options = [
            'url' => $this->url,
            'asset_path' => $this->url . '/assets'
        ];
        $urlFunction = new \Twig\TwigFunction('url', function ($slug) use ($options) {
            return $options['url'] . $slug;
        });
        $assetFunction = new \Twig\TwigFunction('asset', function ($asset) use ($options) {
            return $options['asset_path'] . $asset;
        });
        $twig->addFunction($urlFunction);
        $twig->addFunction($assetFunction);

        $this->instance = $twig;
    }

    /**
     * @param string $template
     * @param array<string, mixed> $data
     * @return string
     */
    public function render(string $template, array $data = []): string
    {
        try {
            return $this->instance->render($template, $data);
        } catch (\Twig\Error\LoaderError | \Twig\Error\SyntaxError | \Twig\Error\RuntimeError $e) {
            throw new InternalError("Error Processing Template");
        }
    }

    /**
     * \Twig\Environment addGlobal method
     * @param mixed $value The global value
     */
    public function addGlobal(string $name, $value)
    {
        $this->instance->addGlobal($name, $value);
    }
}
