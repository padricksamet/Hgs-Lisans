<?php
require_once 'config.php';
require_once 'auth.php';

$error = $success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Rastgele 32 bayt lisans anahtarı üret (ham hali)
    $raw_key = bin2hex(random_bytes(32));
    $hash = hash('sha256', $raw_key);

    $status = $_POST['status'] ?? 'active';
    $plan = $_POST['plan'] ?? '';
    $domain = $_POST['domain'] ?: null;
    $expires_at = $_POST['expires_at'] ?: null;
    $max_activations = (int)($_POST['max_activations'] ?? 1);

    try {
        $stmt = $pdo->prepare("INSERT INTO licenses (license_key_hash, status, plan, domain, expires_at, max_activations)
                                VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$hash, $status, $plan, $domain, $expires_at, $max_activations]);
        $success = "Lisans oluşturuldu! <br> <strong>Orijinal Anahtar (kaydedin):</strong> <code>$raw_key</code>";
    } catch (PDOException $e) {
        $error = "Hata: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Yeni Lisans</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; margin: 20px; }
        .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; }
        label { display: block; margin: 10px 0 4px; font-weight: bold; }
        input, select { width: 100%; padding: 6px; box-sizing: border-box; }
        button { padding: 8px 20px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
<div class="container">
    <h2>Yeni Lisans Ekle</h2>
    <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
    <?php if ($success): ?><p class="success"><?= $success ?></p><?php endif; ?>
    <form method="post">
        <label>Durum</label>
        <select name="status">
            <option value="active">Aktif</option>
            <option value="inactive">Pasif</option>
            <option value="expired">Süresi Dolmuş</option>
        </select>

        <label>Plan</label>
        <input type="text" name="plan" placeholder="ör: standard, premium" required>

        <label>Domain (opsiyonel)</label>
        <input type="text" name="domain" placeholder="example.com">

        <label>Bitiş Tarihi (boş bırak = süresiz)</label>
        <input type="datetime-local" name="expires_at">

        <label>Maks. Aktivasyon Sayısı</label>
        <input type="number" name="max_activations" value="1" min="1">

        <br><br>
        <button type="submit">Oluştur</button>
        <a href="index.php" style="margin-left:10px;">Geri</a>
    </form>
</div>
</body>
</html>