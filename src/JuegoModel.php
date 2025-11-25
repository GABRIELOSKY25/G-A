<?php
// src/JuegoModel.php

// Asegurarse de que la configuración de la DB esté cargada
require_once __DIR__ . '/../config/db_config.php';

class JuegoModel {
    private $pdo;

    public function __construct() {
        $this->pdo = getDBConnection();
    }

    /**
     * Obtiene todos los juegos 'Activos' con sus categorías y plataformas.
     * @return array Array de juegos o un array vacío en caso de error.
     */
    public function obtenerTodosLosJuegos() {
        // Esta consulta hace un JOIN para obtener los nombres completos de Categoría y Plataforma
        $sql = "
            SELECT
                p.id_juego, p.nombre, p.imagen, p.precio, p.oferta, p.sinopsis,
                c.categoria, plt.plataforma
            FROM
                Producto p
            INNER JOIN
                Categoria c ON p.id_categoria = c.id_categoria
            INNER JOIN
                Plataforma plt ON p.id_plataforma = plt.id_plataforma
            WHERE
                p.estado = 'Activo'
            ORDER BY
                p.nombre ASC
        ";

        try {
            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            // Manejo de errores: en un entorno de producción, registra el error
            error_log("Error al obtener juegos: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Lógica simplificada para obtener juegos destacados (ejemplo)
     * En un proyecto real, esto podría ser un campo 'destacado' en la DB.
     * Por simplicidad, obtenemos los 3 primeros.
     */
    public function obtenerJuegosDestacados($limite = 3) {
        $sql = "
            SELECT
                p.id_juego, p.nombre, p.imagen, p.precio, p.oferta, p.sinopsis
            FROM
                Producto p
            WHERE
                p.estado = 'Activo'
            ORDER BY
                p.id_juego DESC -- Muestra los más recientes o destacados
            LIMIT :limite
        ";

        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            error_log("Error al obtener destacados: " . $e->getMessage());
            return [];
        }
    }
}