<?php

namespace Shiblati\Framework\Extensions\Twig;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;

/**
 * Class DotenvExtension
 */
class DotenvExtension extends AbstractExtension
{
    /**
     * Define env accessor function
     * 
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('env', 'env'),
        ];
    }
}