<?php

    namespace App\Model;
    
    use PDOException;

    class WarehouseModel extends AbstractModel {

        public function __construct() {

            parent::__construct();

        }

        public function getAllWarehouses(): array {

            try {

                $sql = "SELECT * FROM warehouse;";

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

        public function newWarehouse(string $name, string $description, int $maxQuantity): bool {

            try {

                $sql = "INSERT INTO warehouse (name, description, maxQuantity) VALUES (:name, :description, :maxQuantity);";

                $stmt = $this->pdo->prepare($sql);

                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':maxQuantity', $maxQuantity);

                return $stmt->execute();

            }
            catch (PDOException $e) {

                // Render PDOException into the template
                $this->controller->renderError('errors/pdo.html', [$e->getMessage()]);
                die();

            }

        }

        public function getWarehouseOverviewData(): mixed {

            try {

                $sql = "SELECT warehouse_manager.*, 
                               article.name AS articleName, 
                               article.description AS articleDescription, 
                               article.quantity AS articleQuantity, 
                               article.minQuantity AS articleMinQuantity,
                               article.createdAt AS articleCreatedAt, 
                               warehouse.name AS warehouseName,                                                              
                               user.username
                        FROM warehouse_manager 
                        INNER JOIN article ON warehouse_manager.articleID = article.articleID
                        INNER JOIN warehouse ON warehouse_manager.warehouseID = warehouse.warehouseID
                        INNER JOIN user ON warehouse_manager.createdBy = user.userID;";

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