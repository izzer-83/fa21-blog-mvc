<?php

    /*
    * author: tk
    * name: fa21-blog-mvc
    * date: 29.08.22
    */

    namespace App\Model;
    
    use App\Traits\Encryption;
    use App\Traits\UUID;
    use App\Traits\UserInput;
    
    use PDOException;   
    
    class AuthModel extends AbstractModel {

        use Encryption;
        use UUID;
        use UserInput;

        public function __construct() {

            parent::__construct();

        }

        /**
         * Creates a new User
         * 
         * @param string $username
         * @param string $password
         * @param string $email
         * 
         * @return bool
         */ 
        public function newUser(string $username, string $password, string $email): bool {

            $username = $this->getCleanString($username);
            $password = $this->getCleanString($password);
            $email = $this->getCleanString($email);

            $hashed_password = $this->encrypt($password);
            $publicID = $this->UUIDv4();

            try {
                $sql = "INSERT INTO user (publicID, username, password, email) VALUES (:publicID, :username, :password, :email)";
            
                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':publicID', $publicID);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':password', $hashed_password);
                $stmt->bindParam(':email', $email);

                $stmt->execute();
            }
            catch (PDOException $e) {

                $error_temp = $this->controller->view->load('errors/pdo.html');
                echo $error_temp->render(['msg' => $e->getMessage()]);

            }
            
            return true;
            
        }

        /**
         * Fetch a user by username
         * 
         * @param string $username
         * 
         * @return mixed
         */ 
        public function getUserByName(string $username) {

            $username = $this->getCleanString($username);

            try {

                $sql = "SELECT * FROM user WHERE username = :username";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':username', $username);
                $stmt->execute();
                
                $res = $stmt->fetch();

                if ($res == false) { return false; }

                return $res;
            }
            catch (PDOException $e) {
                
                $this->controller->renderError('auth/login.html', [$e->getMessage()]);

            }

        }

        /**
         * Fetch a user by email
         * 
         * @param string $email
         * 
         * @return mixed
         */ 
        public function getUserByEmail(string $email): mixed {

            $email = htmlentities($email);

            try {

                $sql = "SELECT * FROM user WHERE email = :email";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':email', $email);
                $stmt->execute();
                
                $res = $stmt->fetch();

                if ($res == false) { return false; }
                
                return $res;
            }
            catch (PDOException $e) {

                $this->controller->renderError('auth/login.html', [$e->getMessage()]);

            }

        }

    }