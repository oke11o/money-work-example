<?php

namespace App\Kernel;

use App\DataMapper\MapperRepository;
use App\Entity\Transaction;
use App\Entity\User;
use App\Exception\Kernel\KernelException;
use App\Kernel\Router\Router;
use App\Provider\UserProvider;
use App\Provider\UserProviderInterface;
use App\Security\Authenticator;
use App\Security\Authorizer;
use App\Security\PasswordEncoder;
use PDO;
use PDOException;
use Psr\Container\ContainerInterface;
use Twig_Environment;
use Twig_Loader_Filesystem;

/**
 * Class ContainerBuilder
 * @package App\Kernel
 * @author Sergey Bevzenko <bevzenko.sergey@gmail.com>
 */
class ContainerBuilder
{
    private const TEMPLATE_PATH = 'templates';
    const TWIG_CACHE_DIR = 'var'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'twig';

    /**
     * @param string $rootDir
     * @param array $routes
     * @param array $dbConfig
     * @return ContainerInterface
     */
    public function create(string $rootDir, array $routes, array $dbConfig): ContainerInterface
    {
        $container = new Container();

        $this->initRouter($container, $routes);
        $this->initTwig($container, $rootDir);
        $this->initDataMapper($container, $dbConfig);
        $this->initSecurity($container);

        return $container;
    }

    /**
     * @param Container $container
     * @param array $routes
     */
    private function initRouter(Container $container, array $routes)
    {
        $router = new Router($routes);
        $container->add(Router::class, $router);
    }

    /**
     * @param Container $container
     * @param string $rootDir
     */
    private function initTwig(Container $container, string $rootDir)
    {
        $loader = new Twig_Loader_Filesystem($rootDir.DIRECTORY_SEPARATOR.self::TEMPLATE_PATH);
        $twig = new Twig_Environment(
            $loader,
            [
                'cache' => $rootDir.DIRECTORY_SEPARATOR.self::TWIG_CACHE_DIR,
            ]
        );
        $twig->addExtension(new TwigExtension($container->get(Router::class)));

        $container->add(Twig_Environment::class, $twig);
    }

    /**
     * @param Container $container
     * @param array $dbConfig
     * @throws \App\Exception\Kernel\KernelException
     */
    private function initDataMapper(Container $container, array $dbConfig)
    {
        $list = [
            User::class,
            Transaction::class,
        ];
        $pdo = self::createPdo($dbConfig);

        $mapperRepository = new MapperRepository($pdo, $list);

        $container->add(MapperRepository::class, $mapperRepository);
    }

    /**
     * @param Container $container
     */
    private function initSecurity(Container $container)
    {
        $userProvider = new UserProvider();
        $container->add(UserProviderInterface::class, $userProvider);

        $encoder = new PasswordEncoder();
        $container->add(PasswordEncoder::class, $encoder);

        $authorizer = new Authorizer($userProvider);
        $container->add(Authorizer::class, $authorizer);

        $user = $authorizer->getAuthUser();
        $container->add(User::class, $user);

        $authenticator = new Authenticator($userProvider, $encoder);
        $container->add(Authenticator::class, $authenticator);
    }


    /**
     * @return PDO
     * @throws KernelException
     */
    private function createPdo($config): PDO
    {
        try {
            $pdo = new PDO(
                sprintf('mysql:host=%s;dbname=%s', $config['host'], $config['name']),
                $config['user'],
                $config['password']
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new KernelException('Connection failed: '.$e->getMessage(), 0, $e);
        }

        return $pdo;
    }
}