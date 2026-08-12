<?php
require_once 'config.php';
require_once 'auth.php';

$id = (int)($_GET['id'] ?? 0);
if (!$id) die('Geçersiz ID');

$stmt = $pdo->prepare("SELECT * FROM licenses WHERE id = ?");
$stmt->execute([$id]);
$license = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$license) die('Lisans bulunamadı');

$error = $success = '';

// Silme işlemi
if (isset($_GET['delete'])) {
    $pdo->prepare("DELETE FROM licenses WHERE id = ?")->execute([$id]);
    header('Location: index.php');
    exit;
}

// Güncelleme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'];
    $plan = $_POST['plan'];
    $domain = $_POST['domain'] ?: null;
    $expires_at = $_POST['expires_at'] ?: null;
    $max_activations = (int)$_POST['max_activations'];

    try {
        $stmt = $pdo->prepare("UPDATE licenses SET status=?, plan=?, domain=?, expires_at=?, max_activations=?, updated_at=NOW() WHERE id=?");
        $stmt->execute([$status, $plan, $domain, $expires_at, $max_activations, $id]);
        $success = "Güncellendi!";
        // yeniden yükle
        $stmt = $pdo->prepare("SELECT * FROM licenses WHERE id = ?");
        $stmt->execute([$id]);
        $license = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $error = "Hata: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lisans Düzenle</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; margin: 20px; }
        .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 8px; }
        label { display: block; margin: 10px 0 4px; font-weight: bold; }
        input, select { width: 100%; padding: 6px; box-sizing: border-box; }
        button { padding: 8px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn-danger { background: #dc3545; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
<div class="container">
    <h2>Lisans Düzenle (ID: <?= $id ?>)</h2>
    <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>
    <?php if ($success): ?><p class="success"><?= $success ?></p><?php endif; ?>
    <form method="post">
        <label>Durum</label>
        <select name="status">
            <option value="active" <?= $license['status']=='active'?'selected':'' ?>>Aktif</option>
            <option value="inactive" <?= $license['status']=='inactive'?'selected':'' ?>>Pasif</option>
            <option value="expired" <?= $license['status']=='expired'?'selected':'' ?>>Süresi Dolmuş</option>
        </select>

        <label>Plan</label>
        <input type="text" name="plan" value="<?= htmlspecialchars($license['plan']) ?>" required>

        <label>Domain</label>
        <input type="text" name="domain" value="<?= htmlspecialchars($license['domain'] ?? '') ?>">

        <label>Bitiş Tarihi</label>
        <input type="datetime-local" name="expires_at" value="<?= $license['expires_at'] ? date('Y-m-d\TH:i', strtotime($license['expires_at'])) : '' ?>">

        <label>Maks. Aktivasyon</label>
        <input type="number" name="max_activations" value="<?= $license['max_activations'] ?>" min="1">

        <br><br>
        <button type="submit">Güncelle</button>
        <a href="index.php">Geri</a>
        <a href="?id=<?= $id ?>&delete=1" onclick="return confirm('Silmek istediğinize emin misiniz?')" class="btn-danger" style="float:right; background:#dc3545; color:white; padding:6px 16px; text-decoration:none; border-radius:4px;">Sil</a>
    </form>
</div>
</body>
</html>