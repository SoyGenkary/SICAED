<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/SICAED/app/Models/Browser.php';

class SearchController {
    public static function search($conn, $data) {
        return Search::ejecutarBusqueda($conn, $data);
    }
}
