<?php
require_once '../models/Feed.php';
require_once '../models/News.php';
require_once '../vendor/autoload.php';

use SimplePie\SimplePie;

class FeedService {
    private $conn;
    private $feedModel;
    private $newsModel;

    public function __construct($conn) {
        $this->conn = $conn;
        $this->feedModel = new Feed($conn);
        $this->newsModel = new News($conn);
    }

    public function fetchAndSaveNews() {
        $feeds = $this->feedModel->getFeeds(); // Obtiene los feeds desde la DB

        foreach ($feeds as $feed) {
            $feed_id = $feed['id'];
            $url = $feed['url'];

            //Creamos una instancia de SimplePie
            $pie = new SimplePie();
            $pie->enable_cache(false);
            $pie->set_feed_url($url);
            $pie->init();

            if ($pie->error()) {
                error_log("Error al obtener el feed: " . $pie->error());
                continue; // Si hay un error con el feed, saltarlo
            }
            
            // Iterar sobre los items del feed
            foreach ($pie->get_items() as $item) {
                $title = $item->get_title();
                $description = $item->get_description();
                $link = $item->get_permalink();
                $pub_date = $item->get_date('Y-m-d H:i:s');

                //Obtener categorias
                $categories = [];
                if ($item_categories = $item->get_categories()) {
                    foreach ($item_categories as $category) {
                        $categories[] = $category->get_label();
                    }
                }
                $categories_json = !empty($categories) ? json_encode($categories) : null ;

                $this->newsModel->addNews($feed_id, $title, $description, $link, $pub_date, $categories_json);
            }

            // Liberar recursos
            $pie->__destruct();
            unset($pie);
        }

        return ["success" => true, "message" => "Noticias actualizadas"];
    }
}
?>
