<?php

function getFeedNameFromUrl($url) {
    // Intentar cargar el XML desde la URL
    $xmlContent = @simplexml_load_file($url);

    // Validar si se pudo cargar correctamente
    if (!$xmlContent) {
        throw new Exception("Error al leer el XML desde la URL.");
    }

    // Intentar obtener el nombre del feed (esto puede variar según el XML)
    return isset($xmlContent->channel->title) ? (string) $xmlContent->channel->title : "Feed Desconocido";
}

?>
