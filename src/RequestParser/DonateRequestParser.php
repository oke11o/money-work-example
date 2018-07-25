<?php

namespace App\RequestParser;

use App\Enum\AvailableCurrencyEnum;
use App\Factory\MoneyFactory;
use App\Kernel\Http\Request;
use DI\Annotation\Injectable;
use Money\Money;

/**
 * Class DonateRequestParser
 * @package App\RequestParser
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 *
 * @Injectable(lazy=true)
 */
class DonateRequestParser
{
    /**
     * @var MoneyFactory
     */
    private $moneyFactory;

    public function __construct(MoneyFactory $moneyFactory)
    {
        $this->moneyFactory = $moneyFactory;
    }
    /**
     * @param Request $request
     * @return Money
     */
    public function parse(Request $request)
    {
        $post = $request->getPost();
        if (!$post || !isset($post['donate'])) {
            throw new \RuntimeException('Empty fields');
        }

        $donate = (float) str_replace(',', '.', $post['donate'] ?? '0');
        if (!$donate) {
            throw new \RuntimeException('Null donate');
        }

        return $this->moneyFactory->createFromFloat($donate, AvailableCurrencyEnum::RUB);
    }
}