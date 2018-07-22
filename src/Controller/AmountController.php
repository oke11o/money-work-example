<?php

namespace App\Controller;

use App\Exception\Manager\UserManagerException;
use App\Kernel\Http\Request;
use App\Kernel\Http\Response;
use App\Manager\UserManager;
use Money\Money;

/**
 * Class AmountController
 * @package App\Controller
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class AmountController extends BaseController
{
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

        return $this->render('amount/index.html.twig', [
            'successMessage' => $message,
            'errorMessage' => $errorMessage,
        ]);
    }

    /**
     * @param Request $request
     * @return \App\Kernel\Http\RedirectResponse
     */
    public function donate(Request $request): Response
    {
        $post = $request->getPost();
        if (!$post) {
            $message = 'Empty fields';

            return $this->redirectToRoute('amount', ['error_message' => $message]);
        }

        $donate = str_replace(',','.', $post['donate']) ?? 0;
        $donate = (float)$donate;
        if (!$donate) {
            $message = 'Null donate';

            return $this->redirectToRoute('amount', ['error_message' => $message]);
        }

        $donate = Money::RUB(100 * $donate);

        $user = $this->getUser();
        if (!$user) {
            $message = 'User not authorized';

            return $this->redirectToRoute('amount', ['error_message' => $message]);
        }

        /** @var UserManager $userManager */
        $userManager = $this->container->get(UserManager::class);
        try {
            $userManager->withdraw($user, $donate);
            $message = 'success';
        } catch (UserManagerException $exception) {
            $message = $exception->getMessage();

            return $this->redirectToRoute('amount', ['error_message' => $message]);
        }

        return $this->redirectToRoute('amount', ['message' => $message]);

    }
}