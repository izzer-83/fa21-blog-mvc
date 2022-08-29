<?php

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