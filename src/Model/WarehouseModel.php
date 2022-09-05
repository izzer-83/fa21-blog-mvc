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

    }