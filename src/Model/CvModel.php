<?php

namespace App\Model;

use PDO;

class CvModel
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    // Devuelve las etiquetas de la interfaz según el idioma
    public function getUiLabels(string $lang = 'es')
    {
        $stmt = $this->pdo->prepare("SELECT clave, texto FROM etiquetas_ui WHERE id_local = ?");
        $stmt->execute([$lang]);
        $labels = [];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $labels[$row['clave']] = $row['texto'];
        }

        return $labels;
    }

    // Obtiene todos los datos del CV estructurados por idioma
    public function getFullCv(string $lang = 'es')
    {
        // Datos del usuario base (no se traducen)
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id_usuario = ?");
        $stmt->execute([1]);
        $usuario = $stmt->fetch();

        // Resumen del usuario según idioma
        $stmt = $this->pdo->prepare("SELECT resumen FROM usuario_i18n WHERE id_usuario = ? AND id_local = ?");
        $stmt->execute([1, $lang]);
        $resumen = $stmt->fetch()['resumen'] ?? '';

        // Experiencia laboral + descripción i18n
        $stmt = $this->pdo->prepare("
            SELECT e.*, ei.descripcion 
            FROM experiencia e
            LEFT JOIN experiencia_i18n ei ON e.id_experiencia = ei.id_experiencia AND ei.id_local = ?
            WHERE e.id_usuario = ?
            ORDER BY fecha_fin DESC
        ");
        $stmt->execute([$lang, 1]);
        $experiencia = $stmt->fetchAll();

        // Educación (no cambia por idioma, pero puedes crear una tabla educacion_i18n si lo deseas
        $stmt = $this->pdo->prepare("SELECT * FROM educacion WHERE id_usuario = ?");
        $stmt->execute([1]);
        $educacion = $stmt->fetchAll();

        // Idiomas + niveles según idioma
        $stmt = $this->pdo->prepare("SELECT i.nombre, n.* FROM idiomas i LEFT JOIN idioma_nivel n ON i.id_idioma = n.id_idioma AND n.id_local = ?");
        $stmt->execute([$lang]);
        $idiomas = $stmt->fetchAll();

        // ✅ HABILIDADES – ahora usando la versión i18n
        $stmt = $this->pdo->prepare("SELECT nombre FROM habilidad_i18n WHERE id_local = ?");
        $stmt->execute([$lang]);
        $habilidades = array_column($stmt->fetchAll(), 'nombre');

        // Etiquetas de UI (títulos de secciones)
        $uiLabels = $this->getUiLabels($lang);

        // Devolver todo como array asociativo
        return compact('usuario', 'resumen', 'experiencia', 'educacion', 'idiomas', 'habilidades', 'uiLabels');
    }
}