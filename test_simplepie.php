<?php

require_once './vendor/autoload.php';

use SimplePie\SimplePie;

try {

    $feed = new SimplePie();
    $feed->enable_cache(false);

    $feed->set_feed_url('https://feed.perfplanet.com/');
    $feed->init();
    $feedName = $feed->get_title();
    echo "El nombre del feed es: " . $feedName;

    $feed->__destruct();
    unset($feed);


} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

?>