<?php

namespace App\Controller;

use App\Exception\Security\InvalidFormKeysException;
use App\Exception\Security\SecurityException;
use App\Kernel\Http\Request;

/**
 * Class LoginController
 * @package App\Controller
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class LoginController extends BaseController
{
    public const ACTION_NAME_LOGIN = 'login';
    public const ACTION_NAME_LOGOUT = 'logout';
    /**
     * @param Request $request
     * @return \App\Kernel\Http\RedirectResponse|\App\Kernel\Http\Response
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index(Request $request)
    {
        $post = $request->getPost();
        $errors = '';
        if (!empty($post)) {
            try {
                if (!isset($post['email'], $post['password'])) {
                    throw new InvalidFormKeysException('Require next form fields: "email", "password"');
                }
                $user = $this->getAuthenticator()->authenticate($post['email'], $post['password']);

                $this->getAuthorizer()->saveUserToSession($user);

                return $this->redirectToRoute('amount');
            } catch (SecurityException $e) {
                $errors = 'Invalid email or password';
            }
        }

        return $this->render('login/index.html.twig', ['errors' => $errors, 'post' => $post]);
    }

    /**
     * @param Request $request
     * @return \App\Kernel\Http\RedirectResponse
     * @throws \Exception
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function logout(Request $request)
    {
        $this->getAuthorizer()->logout();

        return $this->redirectToRoute('home');
    }
}