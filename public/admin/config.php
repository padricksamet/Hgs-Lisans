<?php
// admin/config.php
session_start();

// Veritabanı bağlantısı (PDO)
$host = '76t8f9.stackhero-network.com';
$port = '7335';
$dbname = 'postgres';   // muhtemelen varsayılan, kontrol edin
$user = 'admin';
$pass = 'ev0NLpNgncLTVqK8sIVIPRFggvbgRUh1';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}

// Admin paneli giriş bilgileri (basit, değiştirebilirsiniz)
define('ADMIN_USER', 'admin');
define('ADMIN_PASS_HASH', password_hash('admin123', PASSWORD_DEFAULT)); // şifre: admin123
// İsterseniz bunu bir .env dosyasından okuyun.
?>