<?php

    /*
    * author: tk
    * name: fa21-blog-mvc
    * date: 29.08.22
    */

    namespace App\Model;
    
    // Project-Imports
    use App\Traits\Encryption;
    use App\Traits\UUID;
    use App\Traits\UserInput;
    
    // Package-Imports
    use PDOException;   
    
    class AuthModel extends AbstractModel {

        // Use Traits
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

                // Render PDOException into the template
                $this->controller->renderError('errors/pdo.html', [$e->getMessage()]);
                die();

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
        public function getUserByUserID(int $userID): mixed {

            $userID = $this->getCleanString($userID);

            try {

                $sql = "SELECT * FROM user WHERE userID = :userID";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':userID', $userID);
                $stmt->execute();
                
                $res = $stmt->fetch();

                if ($res == false) { return false; }

                return $res;
            }
            catch (PDOException $e) {
                
                // Render PDOException into the template
                $this->controller->renderError('errors/pdo.html', [$e->getMessage()]);
                die();

            }

        }

        /**
         * Fetch a user by username
         * 
         * @param string $username
         * 
         * @return mixed
         */ 
        public function getUserByName(string $username): mixed {

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
                
                // Render PDOException into the template
                $this->controller->renderError('errors/pdo.html', [$e->getMessage()]);
                die();

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

                // Render PDOException into the template
                $this->controller->renderError('errors/pdo.html', [$e->getMessage()]);
                die();

            }

        }

        public function getAllUsers(): mixed {

            try {

                $sql = "SELECT * FROM user INNER JOIN user_role ON user.roleID = user_role.user_roleID;";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                
                $res = $stmt->fetchAll();

                if ($res == false) { return false; }

                return $res;
            }
            catch (PDOException $e) {
                
                // Render PDOException into the template
                $this->controller->renderError('errors/pdo.html', [$e->getMessage()]);
                die();

            }

        }

        public function updateUserByUserID(int $userID, string $username, string $email, int $userRole, string $newPassword = ''): bool {

            if ($newPassword == '') {

                try {

                    $sql = "UPDATE user SET username = :username, email = :email, roleID = :roleID WHERE userID = :userID;";

                    $stmt = $this->pdo->prepare($sql);
                    $stmt->bindParam(':userID', $userID);
                    $stmt->bindParam(':roleID', $userRole);
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':email', $email);
                    

                    return $stmt->execute();
                }
                catch (PDOException $e) {

                    // Render PDOException into the template
                    $this->controller->renderError('errors/pdo.html', [$e->getMessage()]);
                    die();

                }

            }
            else {

                try {  

                    $hashedPassword = $this->encrypt($newPassword);

                    $sql = "UPDATE user SET username = :username, email = :email, isAdmin = :isAdmin, password = :password WHERE userID = :userID;";

                    $stmt = $this->pdo->prepare($sql);
                    $stmt->bindParam(':userID', $userID);
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $hashedPassword);
                    $stmt->bindParam(':isAdmin', $isAdmin);

                    return $stmt->execute();

                }
                catch (PDOException $e) {

                    // Render PDOException into the template
                    $this->controller->renderError('errors/pdo.html', [$e->getMessage()]);
                    die();

                }

            }

        }

        public function deleteUser(int $userID): bool {

            try {

                $sql = "DELETE FROM user WHERE userID = :userID";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':userID', $userID);

                $stmt->execute();

                return true;
            }
            catch (PDOException $e) {

                // Render PDOException into the template
                $this->controller->renderError('errors/pdo.html', [$e->getMessage()]);
                die();

            }

        }

        public function getAllUserRoles(): array {

            try {

                $sql = "SELECT * FROM user_role";

                $stmt = $this->pdo->prepare($sql);                
                $stmt->execute();

                return $stmt->fetchAll();

            }
            catch (PDOException $e) {

                // Render PDOException into the template
                $this->controller->renderError('errors/pdo.html', [$e->getMessage()]);
                die();

            }

        }

        public function newUserRole(string $rolename, string $description): bool {

            try {

                $sql = "INSERT INTO user_role (rolename, description) VALUES (:rolename, :description)";

                $stmt = $this->pdo->prepare($sql);

                return $stmt->execute([
                    ':rolename' => $rolename,
                    ':description' => $description
                ]);

            }
            catch (PDOException $e) {

                // Render PDOException into the template
                $this->controller->renderError('errors/pdo.html', [$e->getMessage()]);
                die();

            }

        }

    }