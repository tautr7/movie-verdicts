<?php
// login.php

require_once __DIR__ . '/bootstrap.php';

$page_title = 'Login';
$page_description = 'Login to Flix';
$page_keywords = 'login, flix, movies, tv shows';

include __DIR__ . '/includes/templates/header.php';

require_once 'src/User.php';

$user = new User($pdo);

// Handle form submission for user login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Attempt to log in the user and provide feedback
    $login_result = $user->login($email, $password);
    if ($login_result) {
        $_SESSION['user'] = $login_result;
        echo "<div class='alert alert-success' role='alert'>Login successful!</div>";
        header('Location: index.php');
        exit;
    } else {
        $login_error = "Invalid email or password.";
    }
}
?>

<div class="container">
    <h1 class="mt-5">Login</h1>

    <?php if (!empty($login_error)): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($login_error); ?>
        </div>
    <?php endif; ?>

    <form id="loginForm" action="login.php" method="POST" class="mt-4">
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

        <button type="submit" class="btn btn-primary">Login</button>
    </form>

    <p class="mt-3">Don't have an account? <a href="register.php">Register here</a>.</p>
</div>

<?php include __DIR__ . '/includes/templates/footer.php'; ?>