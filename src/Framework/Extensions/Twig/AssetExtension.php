<?php

namespace Shiblati\Framework\Extensions\Twig;

use Exception;
use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

/**
 * Class AssetExtension
 */
class AssetExtension extends AbstractExtension
{

    /** @var string $path */
    private string $path = '';

    /**
     * Define env accessor function.
     *
     * @return array
     */
    public function getFunctions(): array
    {
        $function = new TwigFunction('asset', [$this, 'asset'], []);

        $function->setArguments([]);

        return [$function];
    }

    /**
     * Generate assets url.
     *
     * @return string
     * @throws Exception
     */
    public function asset(): string
    {
        return $this->url($_SERVER['REQUEST_URI'], func_get_args()[0]['file']);
    }

    /**
     * Generate a relative url from the current path.
     *
     * @param string $uri
     * @param string $file
     * @return string
     * @throws Exception
     */
    private function url(string $uri, string $file): string
    {
        if (!preg_match('%^/(?!.*/$)(?!.*[/]{2,})(?!.*\?.*\?)(?!.*\./).*%im', $uri)){
            throw new Exception('Unable to validate ' . $uri . ' for ' . $file);
        }

        $depth = substr_count($uri, '/');
        $prefix = '/';
        if ($depth > 1) {
            $prefix .= str_repeat('../', $depth);
            $this->path = $prefix;
        }

        $parts = pathinfo($file);

        return match ($parts['extension']) {
            "gif", "jpg", "png", "svg", "webp" => $this->path . 'images/' . $file,
            "css" => $this->path . 'css/' . $file,
            "js" => $this->path . 'js/' . $file,
            "pdf" => $this->path . 'docs/' . $file,
        };
    }
}