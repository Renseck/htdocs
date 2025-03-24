<?php

use model\userModel;

require_once __DIR__ . '/../testconfig.php';

class userModelTest
{
    private $userModel;
    private $testResult;
    private $testEmail = "test_user@example.com";
    private $testName = "Test User";
    private $testPassword = "password123";
    private $createdUserId = null;

    // =============================================================================================
    public function __construct()
    {
        $this->userModel = new \model\userModel();
        $this->testResult = new testResult();
    }

    // =============================================================================================
    public function runTests()
    {
        echo "\nRunning userModel tests...\n";

        // Run all test methods
        $this->testCreateUser();
        $this->testGetUserByEmail();
        $this->testCleanup();

        // Print results
        $this->testResult->printResults(get_class($this));
    }

    // =============================================================================================
    private function testCreateUser()
    {
        // If the test email already exists, delete it from the db
        if ($this->userModel->getUserByEmail($this->testEmail))
        {
            $db = \database\databaseConnection::getInstance()->getConnection();
            $stmt = $db->prepare("DELETE FROM users WHERE email = ?");
            $stmt->execute([$this->testEmail]);
        }

        // Create the test user
        $this->createdUserId = $this->userModel->createUser(
            $this->testName,
            $this->testEmail,
            $this->testPassword
        );

        assertTrue($this->createdUserId !== false, "Failed to create user", $this->testResult);
    }

    // =============================================================================================
    private function testGetUserByEmail()
    {
        $user = $this->userModel->getUserByEmail($this->testEmail);
        assertNotNull($user, "User should be found by email", $this->testResult);
        assertEquals($this->testName, $user["name"], "Username should match", $this->testResult);

        $passwordMatches = password_verify($this->testPassword, $user["password"]);
        assertTrue($passwordMatches, "Password should be correctly hashed", $this->testResult);

        $nonExistentUser = $this->userModel->getUserByEmail("nonexistent" . time() . "@example.com");
        assertEquals(false, $nonExistentUser, "Non-existent user should return false", $this->testResult);
    }

    // =============================================================================================
    private function testCleanup()
    {
        if ($this->createdUserId) {
            $db = \database\databaseConnection::getInstance()->getConnection();
            $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
            $stmt->execute([$this->createdUserId]);
            echo "\nCleaned up test user with ID: " . $this->createdUserId;
        }   
    }

    // =============================================================================================
}

// Run the tests
$test = new userModelTest();
$test->runTests();