<?php

use App\DataMapper\MapperRepository;
use App\Entity\Transaction;
use App\Entity\User;
use App\Kernel\Kernel;
use App\Kernel\Router\Router;
use App\Kernel\TwigExtension;
use App\Provider\UserProvider;
use App\Provider\UserProviderInterface;
use Psr\Container\ContainerInterface;

return [
    UserProviderInterface::class => \DI\get(UserProvider::class),

    Router::class => function (ContainerInterface $c) {
        return new Router($c->get(Kernel::DI_CONFIG_KEY_ROUTES));
    },

    \PDO::class => function (ContainerInterface $c) {
        $config = $c->get(Kernel::DI_CONFIG_KEY_DB);
        $dbl = sprintf('mysql:host=%s;dbname=%s', $config['host'], $config['name']);
        $pdo = new PDO(
            $dbl,
            $config['user'],
            $config['password']
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    },

    MapperRepository::class => function (ContainerInterface $c) {
        $list = [
            User::class,
            Transaction::class,
        ];

        return new MapperRepository($c->get(\PDO::class), $list);
    },

    Twig_Environment::class => function (ContainerInterface $c) {
        $templatePath = $c->get(Kernel::DI_CONFIG_KEY_TEMPLATE_PATH);
        $env = $c->get(Kernel::DI_CONFIG_KEY_ENV);
        $twigCacheDir = $c->get(Kernel::DI_CONFIG_KEY_TEMPLATE_CACHE_PATH);

        $loader = new Twig_Loader_Filesystem($templatePath);
        $options = [];
        if ($env === Kernel::ENV_PROD) {
            $options['cache'] = $twigCacheDir;
        } else {
            $options['cache'] = false;
        }

        $twig = new Twig_Environment($loader, $options);
        $extension = $c->get(TwigExtension::class);
        $twig->addExtension($extension);

        return $twig;
    },
];