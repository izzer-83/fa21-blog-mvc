<?php

    namespace App\Traits;

    trait UserStatus {

        private function isLoggedIn(): bool {

            if (!isset($_SESSION) || $_SESSION['isLoggedIn'] == false) {

                return false;

            }

            if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] == true) {

                return true;

            }

            return false;

        }

        private function isAdmin(): bool {

            if (!isset($_SESSION) || $_SESSION['isLoggedIn'] == false || $_SESSION['isAdmin'] == false) {

                return false;

            }

            if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] == true && isset($_SESSION['isAdmin']) && $_SESSION['isAdmin'] == true) {

                return true;

            }

            return false;

        }

    }