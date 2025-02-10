<?php
require_once '../models/Feed.php';
require_once '../models/News.php';

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

            // Obtener noticias del feed (usando SimpleXML para RSS)
            $rss = simplexml_load_file($url,"SimpleXMLElement", LIBXML_NOCDATA);
            if ($rss === false) {
                continue; // Si hay un error con el feed, saltarlo
            }

            foreach ($rss->channel->item as $item) {
                $title = (string) $item->title;
                $description = (string) $item->description;
                $link = (string) $item->link;
                $pub_date = date("Y-m-d H:i:s", strtotime((string) $item->pubDate));

                // Si el feed tiene categorías
                $categories = [];
                if (isset($item->category)) {
                    foreach ($item->category as $category) {
                        $categories[] = (string) $category;
                    }
                }
                $categories_json = !empty($categories) ? json_encode($categories) : null;

                // Guardar la noticia en la DB
                $this->newsModel->addNews($feed_id, $title, $description, $link, $pub_date, $categories_json);
            }
        }

        return ["success" => true, "message" => "Noticias actualizadas"];
    }
}
?>
