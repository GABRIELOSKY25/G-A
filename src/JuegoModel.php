<?php
// src/JuegoModel.php

require_once __DIR__ . '/Database.php'; // Ajusta la ruta si tu archivo está en otro lado

class JuegoModel
{
    private $pdo;

    public function __construct()
    {
        // Si tu clase de conexión se llama diferente, cámbialo aquí.
        $this->pdo = Database::getConnection();
    }

    /**
     * Obtener todos los juegos activos para el catálogo
     */
    public function obtenerTodosLosJuegos()
    {
        $sql = "
            SELECT 
                p.id_juego,
                p.nombre,
                p.imagen,
                p.stock,
                p.precio,
                p.oferta,
                p.sinopsis,
                c.categoria,
                pl.plataforma
            FROM Producto p
            INNER JOIN Categoria c   ON p.id_categoria   = c.id_categoria
            INNER JOIN Plataforma pl ON p.id_plataforma = pl.id_plataforma
            WHERE p.estado = 'Activo'
            ORDER BY p.id_juego DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        // Devuelve un arreglo asociativo
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtener juegos destacados
     * Por ejemplo, los que tienen mayor oferta, limitando el número.
     */
    public function obtenerJuegosDestacados($limite = 3)
    {
        $sql = "
            SELECT 
                p.id_juego,
                p.nombre,
                p.imagen,
                p.stock,
                p.precio,
                p.oferta,
                p.sinopsis,
                c.categoria,
                pl.plataforma
            FROM Producto p
            INNER JOIN Categoria c   ON p.id_categoria   = c.id_categoria
            INNER JOIN Plataforma pl ON p.id_plataforma = pl.id_plataforma
            WHERE p.estado = 'Activo'
            ORDER BY p.oferta DESC, p.id_juego DESC
            LIMIT :limite
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
