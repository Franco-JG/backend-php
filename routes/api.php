<?php
/**
 * Archivo de enrutamiento de la API REST
 * Maneja las diferentes rutas y endpoints de la aplicación
 */

// Configurar headers para CORS y tipo de contenido
header("Access-Control-Allow-Origin: *");       // Permitir acceso desde cualquier origen
header("Content-Type: application/json");       // Establecer tipo de respuesta como JSON

// Importar controladores y crear instancias
require_once '../config/database.php';          // Primero la conexión
require_once '../controllers/FeedController.php';    // Controlador de feeds
require_once '../controllers/NewsController.php';    // Controlador de noticias
require_once '../services/FeedService.php';

$feedService = new FeedService($conn);
// Crear instancias de los controladores
$feedController = new FeedController($conn);
$newsController = new NewsController($conn);


// Enrutamiento de endpoints con manejo de errores
try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['feeds'])) {
        // Endpoint: GET /api.php?feeds
        $result = $feedController->getFeeds();
        echo json_encode([
            "success" => true,
            "feeds" => $result
        ]);
    } 
    elseif (isset($_GET['news'])) {
        // Endpoint: GET /api.php?news
        $result = $newsController->getNews();
        echo json_encode([
            "success" => true,
            "news" => $result
        ]);
    } 
    elseif (isset($_GET['searchNews']) && isset($_GET['q'])) {
        // Endpoint: GET /api.php?searchNews&q={término}
        $result = $newsController->searchNews($_GET['q']);
        echo json_encode([
            "success" => true,
            "news" => $result
        ]);
    }
    else if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['feeds'])) {
        // Obtener y loggear datos del body
        $rawData = file_get_contents("php://input");
        $data = json_decode($rawData, true);
        // Validar que se hayan proporcionado URL y nombre del feed
        if (!isset($data['url'])) {
            throw new Exception("URL del feed no proporcionada");
        }
        
        $result = $feedController->addFeed($data);
        
        if ($result) {
            http_response_code(201); // Created
            echo json_encode([
                "success" => true,
                "message" => "Feed agregado correctamente"
            ]);
        } else {
            throw new Exception("Error al agregar el feed");
        }
    }
    elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['updateNews'])) {
        $result = $feedService->fetchAndSaveNews();
        echo json_encode($result);
    }
    else {
        throw new Exception("Endpoint no válido");
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "error" => $e->getMessage()
    ]);
}
?>
