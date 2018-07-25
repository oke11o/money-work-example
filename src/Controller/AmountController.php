<?php

namespace App\Controller;

use App\Enum\AvailableCurrencyEnum;
use App\Exception\Manager\UserManagerException;
use App\Kernel\Http\Request;
use App\Kernel\Http\Response;
use App\Manager\UserManager;
use Money\Currency;
use Money\Money;

/**
 * Class AmountController
 * @package App\Controller
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class AmountController extends BaseController
{
    public const ACTION_NAME_AMOUNT = 'amount';
    public const ACTION_NAME_DONATE = 'donate';

    /**
     * @param Request $request
     * @return Response
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Loader
     */
    public function index(Request $request): Response
    {
        $query = $request->getQuery();
        $message = $query['message'] ?? null;
        $errorMessage = $query['error_message'] ?? null;

        return $this->render(
            'amount/index.html.twig',
            [
                'successMessage' => $message,
                'errorMessage' => $errorMessage,
            ]
        );
    }

    /**
     * @param Request $request
     * @return \App\Kernel\Http\RedirectResponse
     */
    public function donate(Request $request): Response
    {
        [$donate, $message] = $this->getDonateParam($request);
        if (!$donate) {
            return $this->redirectToRoute('amount', ['error_message' => $message]);
        }

        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('amount', ['error_message' => 'User not authorized']);
        }

        /** @var UserManager $userManager */
        $userManager = $this->container->get(UserManager::class);
        try {
            $userManager->withdraw($user, $donate);
            $message = 'success';
        } catch (UserManagerException $exception) {
            return $this->redirectToRoute('amount', ['error_message' => $exception->getMessage()]);
        }

        return $this->redirectToRoute('amount', ['message' => $message]);

    }

    /**
     * @param Request $request
     * @return array
     * //TODO:
     */
    private function getDonateParam(Request $request): array
    {
        $post = $request->getPost();
        if (!$post) {
            return [null, 'Empty fields'];
        }

        $donate = str_replace(',', '.', ($post['donate'] ?? '0'));
        $donate = (float)$donate;
        if (!$donate) {
            return [null, 'Null donate'];
        }

        $donate = new Money(100 * $donate, new Currency(AvailableCurrencyEnum::RUB));

        return [$donate, null];
    }
}