<?php
if (extension_loaded('redis')) {
    echo "Redis is installed and enabled!";
} else {
    echo "Redis is not installed.";
}
?>
