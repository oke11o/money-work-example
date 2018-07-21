<?php

namespace App\Kernel;

use App\Kernel\Router\Router;
use Money\Money;
use Twig_Extension;
use Twig_SimpleFunction;
use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;

/**
 * Class TwigExtension
 * @package App\Kernel
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class TwigExtension extends Twig_Extension
{
    /**
     * @var Router
     */
    private $router;
    /**
     * @var ISOCurrencies
     */
    private $isoCurrencies;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions(): array
    {
        return [
            new Twig_SimpleFunction('path', [$this->router, 'generate']),
        ];
    }

    /**
     * @return array|\Twig_Filter[]
     */
    public function getFilters(): array
    {
        return [
            new \Twig_SimpleFilter('money', [$this, 'moneyFormat']),
        ];
    }

    /**
     * @param Money $money
     * @return string
     */
    public function moneyFormat(Money $money): string
    {
        $currencies = $this->getIsoCurrencies();

        $numberFormatter = new \NumberFormatter('ru_RU', \NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

        return $moneyFormatter->format($money);
    }

    /**
     * @return ISOCurrencies
     */
    private function getIsoCurrencies(): ISOCurrencies
    {
        if (!$this->isoCurrencies) {
            $this->isoCurrencies = new ISOCurrencies;
        }
        return $this->isoCurrencies;
    }
}