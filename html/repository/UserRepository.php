<?php

class UserRepository {

    public static function GetCurrent() {
        return (isset($_SESSION["data"]) && isset($_SESSION["token"])) ? json_decode(Encryption::Decrypt($_SESSION["data"], $_SESSION["token"])) : null;
    }

    public static function GetByEmail($email) {
        $user = Database::ExecuteGetOne("user", ["email" => $email]);
        if ($user) {
            $user->password = Encryption::Decrypt($user->password, $user->id);
        }
        return $user;
    }

}
