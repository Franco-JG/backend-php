<?php

require_once "../models/News.php";

class NewsController {
    private $model;

    public function __construct($conn) {
        $this->model = new News($conn);
    }

    public function getNews($orderBy = 'pub_date') {
        return $this->model->getNews($orderBy);
    }

    public function searchNews($query) {
        return $this->model->searchNews($query);
    }

}
?>