<?php
require_once 'config.php';
require_once 'auth.php';

// İstatistikler
$total = $pdo->query("SELECT COUNT(*) FROM licenses")->fetchColumn();
$active = $pdo->query("SELECT COUNT(*) FROM licenses WHERE status = 'active'")->fetchColumn();
$expired = $pdo->query("SELECT COUNT(*) FROM licenses WHERE status = 'expired'")->fetchColumn();

// Lisansları getir
$stmt = $pdo->query("SELECT id, license_key_hash, status, plan, domain, expires_at, max_activations, created_at FROM licenses ORDER BY id DESC");
$licenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lisans Paneli</title>
    <style>
        body { font-family: sans-serif; background: #f4f4f4; margin: 20px; }
        .container { max-width: 1200px; margin: auto; background: white; padding: 20px; border-radius: 8px; }
        .stats { display: flex; gap: 20px; margin-bottom: 20px; }
        .stat { background: #e9ecef; padding: 10px 20px; border-radius: 6px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 8px 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; }
        .btn { padding: 4px 12px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; font-size: 14px; }
        .btn-danger { background: #dc3545; }
        .btn-success { background: #28a745; }
        .status-active { color: green; font-weight: bold; }
        .status-expired { color: red; }
        .status-inactive { color: orange; }
        .header { display: flex; justify-content: space-between; align-items: center; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Lisans Yönetim Paneli</h1>
        <div>
            <a href="license-create.php" class="btn btn-success">+ Yeni Lisans</a>
            <a href="logout.php" style="margin-left:15px;">Çıkış</a>
        </div>
    </div>

    <div class="stats">
        <div class="stat"><strong>Toplam:</strong> <?= $total ?></div>
        <div class="stat"><strong>Aktif:</strong> <?= $active ?></div>
        <div class="stat"><strong>Süresi Dolmuş:</strong> <?= $expired ?></div>
        <div class="stat"><a href="activations.php">Aktivasyonları Gör</a></div>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Lisans Anahtarı (hash)</th>
                <th>Durum</th>
                <th>Plan</th>
                <th>Domain</th>
                <th>Bitiş</th>
                <th>Maks. Akt.</th>
                <th>Oluşturulma</th>
                <th>İşlem</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($licenses as $row): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><code><?= substr($row['license_key_hash'], 0, 12) ?>…</code></td>
                <td class="status-<?= $row['status'] ?>"><?= $row['status'] ?></td>
                <td><?= htmlspecialchars($row['plan']) ?></td>
                <td><?= htmlspecialchars($row['domain'] ?: '-') ?></td>
                <td><?= $row['expires_at'] ?: 'Süresiz' ?></td>
                <td><?= $row['max_activations'] ?></td>
                <td><?= $row['created_at'] ?></td>
                <td>
                    <a href="license-edit.php?id=<?= $row['id'] ?>" class="btn">Düzenle</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>