<?php
// bootstrap.php

require_once __DIR__ . '/config/env.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/src/SqlLoader.php';
require_once __DIR__ . '/src/User.php';
require_once __DIR__ . '/src/Movie.php';

// Start a session for user management
session_start();

