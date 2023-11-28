<?php

class CoreController {

    public static function Core(&$response, $post, $args) {
        $response->title = "Error";
        $response->message = "You are not authorized to view this page.";
        $response->payload = false;
        $response->status = "error";
    }

}
