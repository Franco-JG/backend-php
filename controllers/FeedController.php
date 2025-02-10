<?php

require_once "../models/Feed.php";

class FeedController {
    private $model;

    public function __construct($conn) {
        $this->model = new Feed($conn);
    }

    public function getFeeds() {
        return $this->model->getFeeds();
    }

    public function addFeed($url) {
        return $this->model->addFeed($url);
    }
}
?>