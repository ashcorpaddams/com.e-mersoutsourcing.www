<?php

class UserController {

    public static function Login(&$template, &$response) {
        $template = 'login.phtml';
        $response = [
            "title" => "Login",
            "ng_module" => ["user"]
        ];
    }

    public static function LoginApi(&$response, $post) {
        if (!Validate::Post($post, ["email", "password"])) {
            $response->title = "Erreur";
            $response->message = "Certains champs n'ont pas été rempli.";
            $response->status = "error";
        } else {
            $user = UserRepository::GetByEmail($post->email);
            if ($user && $user->password == $post->password) {
                $_SESSION["token"] = md5(time());
                $_SESSION["data"] = Encryption::Encrypt(json_encode($user), $_SESSION["token"]);

                $response->title = "Welcome";
                $response->message = "Welcome back!.";
                $response->status = "success";
                $response->redirect = "/pages";
            } else {
                $user->password = null;
                $response->title = "Error";
                $response->message = "Invalid E-mail or Password.";
                $response->status = "error";
                $response->payload = $user;
            }
        }
    }

    public static function LogoutApi(&$response, $post) {
        session_destroy();
        $response->title = "Deconnexion";
        $response->message = "Vous etes maintenant deconnecté, au plaisir de vous revoir bientot.";
        $response->status = "success";
        $response->redirect = "/";
    }

}
