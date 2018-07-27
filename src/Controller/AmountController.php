<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\Manager\UserManagerException;
use App\Kernel\Http\Request;
use App\Kernel\Http\Response;

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
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute(LoginController::ACTION_NAME_LOGIN);
        }
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
     *
     * @throws \InvalidArgumentException
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function donate(Request $request): Response
    {
        try {
            $user = $this->getUserOrThrow();

            $donate = $this->getDonateRequestParser()->parse($request);

            $this->getUserManager()->withdraw($user, $donate);
        } catch (UserManagerException|\RuntimeException $exception) {
            return $this->redirectToRoute(AmountController::ACTION_NAME_AMOUNT, ['error_message' => $exception->getMessage()]);
        }

        return $this->redirectToRoute(AmountController::ACTION_NAME_AMOUNT, ['message' => 'success']);

    }
}