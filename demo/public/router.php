<?php

/**
 * Stub instead of .htaccess file
 * Only for built-in PHP server
 * Only for this demo!
 */
if (str_starts_with($_SERVER["REQUEST_URI"], '/file')) {
    include 'index.php';
} else {
    return false;
}
