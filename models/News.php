<?php
/**
 * Clase News - Maneja las operaciones relacionadas con noticias RSS
 * Proporciona funcionalidades para obtener y buscar noticias en la base de datos
 */
class News {
    /** @var mysqli Conexión a la base de datos */
    private $conn;

    /**
     * Constructor de la clase News
     * @param mysqli $db Conexión a la base de datos
     */
    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Obtiene todas las noticias ordenadas por un campo específico
     * @param string $orderBy Campo por el cual ordenar los resultados (por defecto: pub_date)
     * @return array Array asociativo con todas las noticias
     */
    public function getNews($orderBy = "pub_date") {
        $sql = "SELECT * FROM news ORDER BY $orderBy DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Busca noticias por título o descripción
     * @param string $query Término de búsqueda
     * @return array Array asociativo con las noticias que coinciden con la búsqueda
     */
    public function searchNews($query){
        $stmt = $this->conn->prepare("SELECT * FROM news WHERE title LIKE ? OR descripcion LIKE ?");
        $likeQuery = "%$query%";
        $stmt->bind_param("ss",$likeQuery,$likeQuery);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

}
?>