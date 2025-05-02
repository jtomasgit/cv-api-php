<?php

namespace App\Controller;

class CvController
{
    private $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getCv(array $args): void
    {
        // Extraer 'lang' desde POST o usar 'es' por defecto
        $input = json_decode(file_get_contents('php://input'), true);
        $lang = $input['lang'] ?? 'es';

        $model = new \App\Model\CvModel($this->pdo);
        $data = $model->getFullCv($lang);

        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
}