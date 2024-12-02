<?php
require_once __DIR__ . '/bootstrap.php';

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$page_title = 'My Profile';
$page_description = 'User profile and account settings';
$page_keywords = 'profile, account, settings';

include __DIR__ . '/includes/templates/header.php';

// Display success/error messages if they exist
$message = isset($_SESSION['success']) ? $_SESSION['success'] : (isset($_SESSION['error']) ? $_SESSION['error'] : '');
unset($_SESSION['success'], $_SESSION['error']);
?>

<style>
.profile-section {
    background: #fff;
    border-radius: 4px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.section-header {
    background: #f8f9fa;
    padding: 15px;
    border-bottom: 1px solid #dee2e6;
    border-radius: 4px 4px 0 0;
}

.section-body {
    padding: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.alert {
    margin-bottom: 20px;
}

.btn-update {
    margin-top: 10px;
}

.password-section {
    border-top: 1px solid #dee2e6;
    margin-top: 20px;
    padding-top: 20px;
}
</style>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <?php if ($message): ?>
                <div class="alert alert-<?php echo isset($_SESSION['success']) ? 'success' : 'danger'; ?> alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Account Settings</h3>
                </div>
                <div class="panel-body">
                    <form action="update_profile.php" method="POST">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">
                        
                        <!-- Email Section -->
                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" 
                                    value="<?php echo htmlspecialchars($_SESSION['user']['email']); ?>" required>
                            </div>
                        </div>

                        <!-- Password Section -->
                        <div class="password-section">
                            <h4>Change Password</h4>
                            <div class="form-group">
                                <label for="new_password">New Password</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <input type="password" class="form-control" id="new_password" name="new_password" 
                                        placeholder="Leave blank to keep current password">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="confirm_password">Confirm New Password</label>
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                                        placeholder="Confirm new password">
                                </div>
                            </div>
                        </div>

                        <!-- Current Password Section -->
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                <input type="password" class="form-control" id="current_password" name="current_password" 
                                    placeholder="Enter current password to save changes" required>
                            </div>
                            <small class="text-muted">Required to save any changes</small>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="glyphicon glyphicon-floppy-disk"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Account Actions -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Quick Links</h3>
                </div>
                <div class="panel-body">
                    <div class="row text-center">
                        <div class="col-xs-6">
                            <a href="my-reviews.php" class="btn btn-info btn-block">
                                <i class="glyphicon glyphicon-comment"></i> My Reviews
                            </a>
                        </div>
                        <div class="col-xs-6">
                            <a href="favorites.php" class="btn btn-warning btn-block">
                                <i class="glyphicon glyphicon-heart"></i> My Favorites
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/includes/templates/footer.php'; ?>
