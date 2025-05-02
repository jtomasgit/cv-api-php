<?php

use DI\ContainerBuilder;
use App\Controller\CvController;
use Dotenv\Dotenv;
require_once __DIR__ . '/../../vendor/autoload.php';
// Cargar variables desde .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();
$containerBuilder = new \DI\ContainerBuilder();

// Definición de dependencias
$containerBuilder->addDefinitions([
    // Servicio PDO (conexión a base de datos)
    \PDO::class => function () {
        $host = $_ENV['DB_HOST'] ;
        $dbname = $_ENV['DB_NAME'] ;
        $user = $_ENV['DB_USER'] ;
        $pass = $_ENV['DB_PASS'] ;
        $charset = $_ENV['DB_CHARSET'] ;

        $dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ];

        return new \PDO($dsn, $user, $pass, $options);
    },

    // Registro del controlador CvController
    CvController::class => \DI\autowire()
]);

$container = $containerBuilder->build();

return $container;