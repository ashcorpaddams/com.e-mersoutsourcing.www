<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class APIResult {

    public $title = "";
    public $code = "";
    public $message = "";
    public $payload = false;
    public $status = "success";
    public $redirect;

}

class Route {

    private static $app;
    private static $routes;
    private static $public_routes;

    public static function SetApp($application) {
        global $app, $routes, $public_routes;
        $app = $application;
        $routes = [];
        $public_routes = [""];

        $app->add(function ($request, $response, $next) {
            global $public_routes;
            require_once './repository/UserRepository.php';
            $request = $request->withAttribute("config", $this['config']);
            $request = $request->withAttribute("json", (object) json_decode(file_get_contents('php://input')));

            if (array_search($request->getAttribute('route')->getName(), $public_routes)) {
                return $next($request, $response);
            }
            $profile = UserRepository::GetCurrent();
            if ($profile) {
                return $next($request->withAttribute('user', $profile), $response);
            }
            http_response_code(403);
            if (strpos($request->getUri()->getPath(), 'api/')) {
                header('Location: ' . '/api/unauthorized');
            } else {
                header('Location: ' . '/login');
            }
            exit;
        });
    }

    public static function Get($url, $c, $a, $public = 0) {
        global $app, $routes, $public_routes;
        if ($public) {
            $public_routes[] = "$c$a";
        }
        $routes["$c$a"] = ["controller" => $c, "action" => $a];

        $app->get($url, function (Request $request, Response $response, $args) {
            global $routes;
            $route = $routes[$request->getAttribute('route')->getName()];
            $controller = $route["controller"];
            $action = $route["action"];

            header('Content-Type: text/html; charset=UTF-8');
            if ($controller != "Core") {
                require_once "./controllers/{$controller}Controller.php";
            }
            View::$Template = "default.phtml";
            $result = ("{$controller}Controller")::$action($args);

            if (!isset($result['section'])) {
                $result['section'] = "Home";
            }
            if (!isset($result['title'])) {
                $result['title'] = "Home";
            }

            $result['profile'] = UserRepository::GetCurrent();
            $result['config'] = $request->getAttribute('config');

            return $this->view->render($response, View::$Template, $result);
        })->setName("$c$a");
    }

    public static function Post($url, $c, $a, $public = 0) {
        global $app, $routes, $public_routes;
        if ($public) {
            $public_routes[] = "$c$a";
        }
        $routes["$c$a"] = ["controller" => $c, "action" => $a];

        $app->post($url, function (Request $request, Response $response, $args) {
            global $routes;
            $route = $routes[$request->getAttribute('route')->getName()];
            $controller = $route["controller"];
            $action = $route["action"];

            header('Content-Type: text/json; charset=iso-8859-1');
            require_once "./controllers/{$controller}Controller.php";
            $result = ("{$controller}Controller")::$action(
                            (object) json_decode(file_get_contents('php://input')),
                            $args,
                            $_FILES
            );
            $response->getBody()->write(json_encode($result));
            return $response;
        })->setName("$c$a");
    }

}

class Repository {

    public static function Requires(...$repositories) {
        foreach ($repositories as $repository) {
            require_once "./repository/$repository.php";
        }
    }

    public static function GetAll() {
        return Database::ExecuteGetMany(get_called_class()::$table);
    }

    public static function Get($object) {
        return Database::ExecuteGetOne(get_called_class()::$table, $object);
    }

    public static function Set($object) {
        return Database::ExecuteInsertOrUpdate(get_called_class()::$table, $object);
    }

}

class Filter {

    public static function Get($variable_name) {
        return filter_input(INPUT_GET, $variable_name);
    }

    public static function Post($variable_name) {
        return filter_input(INPUT_POST, $variable_name);
    }

    public static function Cookie($variable_name) {
        return filter_input(INPUT_COOKIE, $variable_name);
    }

    public static function Server($variable_name) {
        return filter_input(INPUT_SERVER, $variable_name);
    }

    public static function Session($variable_name) {
        return filter_var(isset($_SESSION[$variable_name]) ? $_SESSION[$variable_name] : null);
    }

    public static function Environment($variable_name) {
        return filter_input(INPUT_ENV, $variable_name);
    }

}

class Encryption {

    public static function Encrypt($text, $key) {
        return openssl_encrypt($text, "AES-128-CTR", $key, 0, '1234567891011121');
    }

    public static function Decrypt($text, $key) {
        return openssl_decrypt($text, "AES-128-CTR", $key, 0, '1234567891011121');
    }

    public static function NewId() {
        $guid = uniqid('', true);
        $namespace = rand(11111, 99999);
        $data = $namespace .
                filter_input(INPUT_SERVER, 'REQUEST_TIME') .
                filter_input(INPUT_SERVER, 'HTTP_USER_AGENT') .
                filter_input(INPUT_SERVER, 'REMOTE_ADDR') .
                filter_input(INPUT_SERVER, 'REMOTE_PORT');
        $hash = strtoupper(hash('ripemd128', $guid . md5($data)));
        $guid = substr($hash, 0, 8) . '-' .
                substr($hash, 8, 4) . '-' .
                substr($hash, 12, 4) . '-' .
                substr($hash, 16, 4) . '-' .
                substr($hash, 20, 12);
        return strtolower($guid);
    }

}

class Validate {

    public static function Login($email, $password) {
        global $config;
        return imap_open('{' . $config['mail']["server"] . '/notls}', $email, $password);
    }

    public static function Post($content, $list) {
        foreach ($list as $item) {
            if (!isset($content->$item) || $content->$item == "") {
                return false;
            }
        }
        return true;
    }

}

class Database {

    public static function ObjectToQuery($object, $joint = ", ") {
        foreach ($object as $key => $value) {
            $item[] = "`$key` = '$value'";
        }
        return implode($joint, $item);
    }

    public static function ExecuteInsertOrUpdate($table, $object) {
        $columns = implode("`,`", array_keys($object));
        $data = implode("','", $object);
        $items = Database::ObjectToQuery($object);
        $sql = "INSERT INTO `$table` (`$columns`) VALUES ('$data') ON DUPLICATE KEY UPDATE $items;";
        return Database::ExecNonQuery($sql);
    }

    public static function ExecuteInsert($table, $object) {
        $columns = implode("`,`", array_keys($object));
        $data = implode("','", $object);
        $sql = "INSERT INTO `$table` (`$columns`) VALUES ('$data')";
        return Database::ExecQuery($sql);
    }

    public static function ExecuteActivate($table, $object) {
        $items = Database::ObjectToQuery($object);
        $sql = "UPDATE `$table` SET `active` = '1' WHERE $items;";
        return Database::ExecQuery($sql);
    }

    public static function ExecuteDisactivate($table, $object) {
        $items = Database::ObjectToQuery($object);
        $sql = "UPDATE `$table` SET `active` = '0' WHERE $items;";
        return Database::ExecQuery($sql);
    }

    public static function ExecuteDelete($table, $object) {
        $items = Database::ObjectToQuery($object);
        $sql = "DELETE FROM `$table` WHERE $items;";
        return Database::ExecQuery($sql);
    }

    public static function ExecuteGetOne($table, $object) {
        if (!isset($object["active"])) {
            $object["active"] = 1;
        }
        $items = Database::ObjectToQuery($object, " AND ");
        $sql = "SELECT * FROM `$table` WHERE $items LIMIT 1;";
        $result = Database::ExecQuery($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                return (object) $row;
            }
        }
        return null;
    }

    public static function ExecuteGetMany($table, $object = []) {
        if (!isset($object["active"])) {
            $object["active"] = 1;
        }
        $items = Database::ObjectToQuery($object, " AND ");
        $sql = "SELECT * FROM `$table` WHERE $items;";
        $result = Database::ExecQuery($sql);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $objects[] = (object) $row;
            }
        }
        return $objects;
    }

    public static function ExecQuerySingle($query) {
        $result = Database::ExecQuery($query);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                return (object) $row;
            }
        }
        return null;
    }

    public static function ExecQueryMany($query) {
        $objects = [];
        $result = Database::ExecQuery($query);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $objects[] = (object) $row;
            }
        }
        return $objects;
    }

    public static function ExecQuery($query) {
        $conn = Database::DB();
        $result = $conn->query(str_replace(["'@{", "}@'"], "", $query));
        if (!$result) {
//            echo "FATAL DB:" . mysqli_errno($conn) . ": " . mysqli_error($conn) . ": " . $query;
            return false;
        }
        return $result;
    }

    public static function ExecNonQuery($sql) {
        $conn = Database::DB();
        $result = $conn->query(str_replace(["'@{", "}@'"], "", $sql));
        if (!$result) {
            //echo "FATAL DB: " . mysqli_errno($conn) . ": " . mysqli_error($conn) . ": " . $sql;
            return false;
        }
        return true;
    }

    private static function DB() {
        global $config;
        $conn = isset($config['db']["password"]) ?
                new mysqli($config['db']["server"], $config['db']["username"], $config['db']["password"]) :
                new mysqli($config['db']["server"], $config['db']["username"]);
        mysqli_select_db($conn, $config['db']["database"]);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $conn->query("SET NAMES utf8");
        return $conn;
    }

}

class Date {

    public static function FrenchDate($date) {
        $month = ["", "Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juliet", "Aout", "Septembre", "Octobre", "Novembre", "Decembre"];
        $date = date_create($date);
        return date_format($date, 'd') . " " . $month[(int) date_format($date, 'm')] . " " . date_format($date, 'Y');
    }

}

class Show {

    public static function Conditional($value, $equality, $show) {
        echo $value == $equality ? $show : "";
    }

    public static function Active($value, $equality) {
        echo $value == $equality ? "active" : "";
    }

}

class View {

    public static $Template = "default.phtml";
    public static $Page;
    public static $Modules = ["core"];

    static function Page() {
        return "./layout/page/" . View::$Page;
    }

    static function Partial($page) {
        return "./layout/partial/$page.php";
    }

    static function CSS(...$csss) {
        View::PathCSS('layoutassets/css', ...$csss);
    }

    static function PathCSS($path, ...$csss) {
        foreach ($csss as $css) {
            echo "<link rel='stylesheet' href='/$path/$css'/>";
        }
    }

    static function JS(...$jss) {
        View::PathJS("layoutassets/js", ...$jss);
    }

    static function PathJS($path, ...$jss) {
        foreach ($jss as $js) {
            echo "<script src='/$path/$js'></script>";
        }
    }

    static function Render($response, $page, $modules = [], $template = null) {
        View::$Template = $template ? $template : View::$Template;
        View::$Modules = array_merge(View::$Modules, $modules);
        View::$Page = $page;
        return $response;
    }

}

class Config {

    static function SiteName() {
        global $config;
        return $config['site']["name"];
    }

}
