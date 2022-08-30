<?php

    /*
    * author: tk
    * name: fa21-blog-mvc
    * date: 29.08.22
    */

    namespace App\Model;

    use App\Model\AbstractModel;
    use App\Model\AuthModel;

    class AdminModel extends AbstractModel {

        private AuthModel $authModel;

        public function __construct() {

            parent::__construct();

            $this->authModel = new AuthModel();

        }

        public function getAllUsers() {

            return $this->authModel->getAllUsers();

        }

        public function getUserByName(string $username) {

            return $this->authModel->getUserByName($username);

        }

        public function getUserByEmail(string $email) {

            return $this->authModel->getUserByEmail($email);

        }

        public function getUserByUserID(int $id) {

            return $this->authModel->getUserByUserID($id);

        }

        public function updateUserByUserID(int $userID, string $username, string $email, bool $isAdmin, string $password = '') {

            $this->authModel->updateUserByUserID($userID, $username, $email, $isAdmin, $password);

        }
        

    }