<?php

    namespace App\Datatypes;

    class User {

        private int $userID;
        private string $publicID;
        private string $username;
        private string $password;
        private string $hashed_password;
        private string $email;
        private string $createdAt;


        /**
         * Get the value of userID
         *
         * @return int
         */
        public function getUserID(): int
        {
                return $this->userID;
        }

        /**
         * Get the value of publicID
         *
         * @return string
         */
        public function getPublicID(): string
        {
                return $this->publicID;
        }

        /**
         * Get the value of username
         *
         * @return string
         */
        public function getUsername(): string
        {
                return $this->username;
        }

        /**
         * Get the value of password
         *
         * @return string
         */
        public function getPassword(): string
        {
                return $this->password;
        }

        /**
         * Get the value of hashed_password
         *
         * @return string
         */
        public function getHashedPassword(): string
        {
                return $this->hashed_password;
        }

        /**
         * Get the value of email
         *
         * @return string
         */
        public function getEmail(): string
        {
                return $this->email;
        }

        /**
         * Get the value of createdAt
         *
         * @return string
         */
        public function getCreatedAt(): string
        {
                return $this->createdAt;
        }

        /**
         * Set the value of userID
         *
         * @param int $userID
         *
         * @return self
         */
        public function setUserID(int $userID): self
        {
                $this->userID = $userID;

                return $this;
        }

        /**
         * Set the value of publicID
         *
         * @param string $publicID
         *
         * @return self
         */
        public function setPublicID(string $publicID): self
        {
                $this->publicID = $publicID;

                return $this;
        }

        /**
         * Set the value of username
         *
         * @param string $username
         *
         * @return self
         */
        public function setUsername(string $username): self
        {
                $this->username = htmlentities($username);

                return $this;
        }

        /**
         * Set the value of password
         *
         * @param string $password
         *
         * @return self
         */
        public function setPassword(string $password): self
        {
                $this->password = htmlentities($password);

                return $this;
        }

        /**
         * Set the value of hashed_password
         *
         * @param string $hashed_password
         *
         * @return self
         */
        public function setHashedPassword(string $hashed_password): self
        {
                $this->hashed_password = $hashed_password;

                return $this;
        }

        /**
         * Set the value of email
         *
         * @param string $email
         *
         * @return self
         */
        public function setEmail(string $email): self
        {
                $this->email = htmlentities($email);

                return $this;
        }

        /**
         * Set the value of createdAt
         *
         * @param string $createdAt
         *
         * @return self
         */
        public function setCreatedAt(string $createdAt): self
        {
                $this->createdAt = $createdAt;

                return $this;
        }
        
    }

?>