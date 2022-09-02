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

        public function getUserCount(): int {

            $sql = "SELECT count(*) FROM user";

            return $this->pdo->query($sql)->fetchColumn();

        }

        public function getLatestUser(): string {

            $sql = "SELECT username FROM user ORDER BY username DESC LIMIT 1";

            return $this->pdo->query($sql)->fetchColumn();

        }
        
    }