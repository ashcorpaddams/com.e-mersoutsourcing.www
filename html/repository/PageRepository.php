<?php

class PageRepository {

    public static function Get() {
        return Database::ExecuteGetMany("page");
    }

    public static function GetByUrl($url) {
        $page = Database::ExecuteGetOne("page", ["url" => $url]);
        return $page;
    }

    public static function SetByUrl($url, $page) {
        $page->content = str_replace("'", "", $page->content);
        $page = Database::ExecuteInsertOrUpdate("page", (array) $page);
        return $page;
    }

    public static function DeleteByUrl($url) {
        $page = Database::ExecuteInsertOrUpdate("page", (array) ["url" => $url, "active" => "{{0}}"]);
        return $page;
    }

}
