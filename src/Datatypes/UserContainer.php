<?php
    
    namespace App\Datatypes;

    class UserContainer {

        private array $users;

        public function __construct() {

            $this->users = array();

        }

        public function add(User $user): self|bool {

            if (!$user instanceof User ) { return false; }
                        
            array_push($this->users, $user);
            
            return $this;

        }

    }