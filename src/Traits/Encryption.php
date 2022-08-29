<?php

    /*
    * author: tk
    * name: fa21-blog-mvc
    * date: 29.08.22
    */

    namespace App\Traits;

    trait Encryption {

        private function encrypt(string $pw): string {

            return password_hash($pw, PASSWORD_DEFAULT);

        }

        private function compare(string $pw, string $hash): bool {

            if (password_verify($pw, $hash)) { return true; }
            return false;

        }

    }