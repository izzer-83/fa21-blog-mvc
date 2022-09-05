<?php

    namespace App\Traits;
    use DateTime;

    trait DateTimeConvert {

        private DateTime $date;

        private function convertToDE(string $date): string {

            $this->date = new DateTime($date);
            return $this->date->format('d.m.y - h:m:s');

        }

    }