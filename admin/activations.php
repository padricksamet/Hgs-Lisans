<?php
require_once 'config.php';
require_once 'auth.php';

$stmt = $pdo->query("SELECT * FROM activations ORDER BY created_at DESC");
$activations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Aktivasyonlar</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; margin: 20px; }
        .container { max-width: 1200px; margin: auto; background: white; padding: 20px; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; }
        code { font-size: 12px; }
    </style>
</head>
<body>
<div class="container">
    <h2>Aktivasyon Listesi <a href="index.php" style="font-size:14px;margin-left:20px;">← Geri</a></h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Lisans (hash)</th>
                <th>Domain</th>
                <th>Installation ID</th>
                <th>Son Görülme</th>
                <th>Oluşturulma</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($activations as $row): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><code><?= substr($row['license_key_hash'],0,12) ?>…</code></td>
                <td><?= htmlspecialchars($row['domain']) ?></td>
                <td><code><?= substr($row['installation_id'],0,16) ?>…</code></td>
                <td><?= $row['last_seen'] ?></td>
                <td><?= $row['created_at'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>