<?php

    /*
    * author: tk
    * name: fa21-blog-mvc
    * date: 29.08.22
    */

    namespace App\Traits;

    trait UserInput {

        private function getCleanString(string $val): string {

            $ret = htmlentities($val);
            $val = htmlspecialchars($ret);
            $val = strip_tags($val);

            return $val;

        }

    }