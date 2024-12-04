<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/SqlLoader.php';

class User
{
    private $pdo;

    // Constructor to initialize PDO instance
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    // Register a new user with email and password
    public function register($email, $password)
    {
        $sql = SqlLoader::load('create_user.sql');
        $stmt = $this->pdo->prepare($sql);

        // Hash the password and assign it to a variable
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Bind parameters to prevent SQL injection
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password_hash', $hashedPassword);

        return $stmt->execute();
    }

    // Login user by verifying email and password
    public function login($email, $password)
    {
        $sql = SqlLoader::load('select_user_by_email.sql');
        $stmt = $this->pdo->prepare($sql);
        // Bind email parameter to prevent SQL injection
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the provided password against the stored hash
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user; // Successful login
        }
        return false; // Failed login
    }
}
