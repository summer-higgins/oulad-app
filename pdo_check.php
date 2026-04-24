<?php
echo "<pre>";
echo "Loaded php.ini: " . php_ini_loaded_file() . "\n\n";
echo "PDO drivers:\n";
print_r(PDO::getAvailableDrivers());
echo "\npgsql loaded: " . (extension_loaded('pgsql') ? 'yes' : 'no') . "\n";
echo "pdo_pgsql loaded: " . (extension_loaded('pdo_pgsql') ? 'yes' : 'no') . "\n";
echo "</pre>";
?>