<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Pattamon Nakvichien">

    <!-- Page Specific -->
    <meta name="description" content="<?php echo htmlspecialchars(string: $page_description) ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars(string: $page_keywords) ?>">
    <title><?php echo htmlspecialchars(string: $page_title) ?></title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- JQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js" defer></script>
    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" defer></script>
</head>

<body>
    <header>
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.php"><img src=assets\logos\logo-no-background.png
                            width="150"></a>
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">
                    <?php if (isset($_SESSION['user'])): ?>
                        <p class="navbar-text navbar-right">Hello,
                            <?php echo htmlspecialchars($_SESSION['user']['email']); ?>
                        </p>
                    <?php endif; ?>
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="index.php">Movies</a></li>
                        <?php if (isset($_SESSION['user'])): ?>
                            <li><a href="logout.php">Logout</a></li>
                        <?php else: ?>
                            <li><a href="login.php">Login</a></li>
                            <li><a href="register.php">Register</a></li>
                        <?php endif; ?>
                    </ul>

                </div>
            </div>
        </nav>
    </header>