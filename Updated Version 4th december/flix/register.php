<?php

require_once __DIR__ . '/bootstrap.php';

$page_title = 'Register';
$page_description = 'Register for Flix';
$page_keywords = 'register, flix, movies, tv shows';

include __DIR__ . '/includes/templates/header.php';

require_once 'src/User.php';

$user = new User($pdo);

// Handle form submission for user registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Check if passwords match
    if ($password !== $confirmPassword) {
        $register_error = "Passwords do not match.";
    } else {
        // Register the user and provide feedback
        $register_result = $user->register($email, $password);
        if ($register_result) {
            echo "<div class='alert alert-success' role='alert'>Registration successful!</div>";
        } else {
            $register_error = "Error registering user.";
        }
    }
}
?>

<div class="container">
    <h1 class="mt-5">Register</h1>

    <?php if (!empty($register_error)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($register_error); ?>
        </div>
    <?php endif; ?>

    <form action="register.php" method="POST" class="mt-4">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required
                value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required minlength="8"
                title="At least 8 characters, including uppercase, lowercase, number, and special character.">
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required
                minlength="8" title="Must match the password.">
        </div>

        <button type="submit" class="btn btn-primary">Register</button>
    </form>

    <p class="mt-3">Already have an account? <a href="login.php">Login here</a>.</p>
</div>

<?php include __DIR__ . '/includes/templates/footer.php'; ?>