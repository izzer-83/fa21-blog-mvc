<?php

    namespace App\Model;
    use PDOException;

    class ArticleModel extends AbstractModel {

        public function __construct() {

            parent::__construct();

        }

        public function newArticle(string $name, string $description, float $quantity, float $minQuantity, int $warehouseID, int $userID): bool {

            try {
                $sql = "INSERT INTO article (name, description, quantity, minQuantity) VALUES (:name, :description, :quantity, :minQuantity);";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':quantity', $quantity);
                $stmt->bindParam(':minQuantity', $minQuantity);

                $stmt->execute();

                $articleID = $this->pdo->lastInsertId();

                $sql = "INSERT INTO warehouse_manager (articleID, warehouseID, createdBy) VALUES (:articleID, :warehouseID, :userID);";

                $stmt = $this->pdo->prepare($sql);

                $stmt->bindParam(':articleID', $articleID);
                $stmt->bindParam(':warehouseID', $warehouseID);
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

        public function getAllArticle(): mixed {

            try {
                $sql = "SELECT * FROM article;";

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

    }