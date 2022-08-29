<?php

    namespace App\Model;
    
    use App\Controller\AbstractController;    
    use App\Settings\DatabaseSettings;
    
    use PDO;
    use PDOException;
    

    class AbstractModel {

        private string $dsn = 'mysql:host=' . DatabaseSettings::DB_HOST . ';dbname=' . DatabaseSettings::DB_NAME;
        protected PDO $pdo;
        protected AbstractController $controller;

        public function __construct() {

            try {

                $this->pdo = new PDO($this->dsn, DatabaseSettings::DB_USERNAME, DatabaseSettings::DB_PASSWORD);
                $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

            }
            catch (PDOException $e) {

                die($e->getMessage());

            }

        }

        public function __destruct() {

           unset($this->pdo);

        }

        /**
         * Sets the actual instance of AbstractController to render error templates
         * 
         * @param AbstractController $controller
         * 
         * @return self
         */
        public function setController(AbstractController $controller): self|bool {

            if (!$controller instanceof AbstractController) { return false; }

            $this->controller = $controller;
            
            return $this;

        }

    }