<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Destinasi - Travel Website</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <?php
    require_once '../includes/config.php';
    require_once '../includes/functions.php';
    
    $destination_id = $_GET['id'] ?? null;
    
    if (!$destination_id) {
        header('Location: ../index.php');
        exit();
    }
    
    // Get destination details
    $stmt = $conn->prepare("SELECT d.*, c.name as category_name 
                           FROM destinations d 
                           LEFT JOIN categories c ON d.category_id = c.id 
                           WHERE d.id = ?");
    $stmt->bind_param("i", $destination_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $destination = $result->fetch_assoc();
    
    if (!$destination) {
        header('Location: ../index.php');
        exit();
    }
    
    // Get related destinations (same category)
    $related_stmt = $conn->prepare("SELECT * FROM destinations 
                                   WHERE category_id = ? AND id != ? 
                                   ORDER BY RAND() LIMIT 3");
    $related_stmt->bind_param("ii", $destination['category_id'], $destination_id);
    $related_stmt->execute();
    $related_destinations = $related_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    ?>

    <div class="destination-detail-layout">
        <!-- Header -->
        <header class="detail-header">
            <nav class="detail-navbar">
                <div class="nav-brand">
                    <h2><a href="../index.php">GoVista.com</a></h2>
                </div>
                <ul class="nav-menu">
                    <li><a href="../index.php">Beranda</a></li>
                    <?php if (isLoggedIn()): ?>
                    <?php if (isAdmin()): ?>
                    <li><a href="../admin/dashboard.php">Dashboard Admin</a></li>
                    <?php else: ?>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="destinations.php">Destinasi</a></li>
                    <li><a href="bookings.php">Booking Saya</a></li>
                    <li><a href="profile.php">Profil</a></li>
                    <?php endif; ?>
                    <li><a href="../logout.php">Logout</a></li>
                    <?php else: ?>
                    <li><a href="../login.php">Login</a></li>
                    <li><a href="../register.php">Daftar</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </header>

        <!-- Main Content -->
        <main class="detail-main">
            <!-- Breadcrumb -->
            <div class="breadcrumb">
                <a href="../index.php">Beranda</a>
                <span>/</span>
                <a href="destinations.php">Destinasi</a>
                <span>/</span>
                <span><?php echo $destination['name']; ?></span>
            </div>

            <!-- Destination Hero -->
            <section class="destination-hero">
                <div class="hero-image">
                    <img src="../assets/images/<?php echo $destination['image'] ? $destination['image'] : 'placeholder.jpg'; ?>"
                        alt="<?php echo $destination['name']; ?>">
                    <div class="hero-overlay">
                        <div class="hero-content">
                            <span class="category-tag"><?php echo $destination['category_name']; ?></span>
                            <h1><?php echo $destination['name']; ?></h1>
                            <p class="location">📍 <?php echo $destination['location']; ?></p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Destination Info -->
            <section class="destination-info">
                <div class="container">
                    <div class="info-grid">
                        <!-- Main Info -->
                        <div class="main-info">
                            <div class="info-section">
                                <h2>Tentang Destinasi</h2>
                                <p class="description">
                                    <?php echo nl2br(htmlspecialchars($destination['description'])); ?></p>
                            </div>

                            <div class="info-section">
                                <h2>Fasilitas & Aktivitas</h2>
                                <div class="facilities">
                                    <?php
                                    // Sample facilities based on category
                                    $facilities = [];
                                    switch(strtolower($destination['category_name'])) {
                                        case 'pantai':
                                            $facilities = ['🏖️ Pantai bersih', '🏄‍♂️ Surfing', '🐠 Snorkeling', '🍽️ Restoran seafood', '🚿 Kamar mandi', '🅿️ Parkir'];
                                            break;
                                        case 'gunung':
                                            $facilities = ['🥾 Hiking trail', '🏕️ Area camping', '📸 Spot foto', '🌅 Sunrise point', '🚻 Toilet umum', '🅿️ Parkir'];
                                            break;
                                        case 'budaya':
                                            $facilities = ['🏛️ Museum', '📚 Pusat informasi', '🎭 Pertunjukan budaya', '🛍️ Souvenir shop', '🍽️ Restoran lokal', '🅿️ Parkir'];
                                            break;
                                        default:
                                            $facilities = ['🍽️ Restoran', '🚻 Toilet', '🅿️ Parkir', '📸 Spot foto', '🛍️ Souvenir shop', '🚗 Akses mudah'];
                                    }
                                    ?>
                                    <div class="facilities-grid">
                                        <?php foreach($facilities as $facility): ?>
                                        <div class="facility-item">
                                            <?php echo $facility; ?>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="info-section">
                                <h2>Tips & Informasi Penting</h2>
                                <div class="tips">
                                    <div class="tip-item">
                                        <h4>🕒 Waktu Terbaik Berkunjung</h4>
                                        <p>Pagi hari (06:00-10:00) atau sore hari (15:00-18:00) untuk cuaca yang lebih
                                            sejuk dan pemandangan terbaik.</p>
                                    </div>
                                    <div class="tip-item">
                                        <h4>👕 Apa yang Harus Dibawa</h4>
                                        <p>Pakaian nyaman, sepatu yang sesuai, topi, sunscreen, kamera, dan air minum.
                                        </p>
                                    </div>
                                    <div class="tip-item">
                                        <h4>⚠️ Hal yang Perlu Diperhatikan</h4>
                                        <p>Jaga kebersihan lingkungan, ikuti petunjuk guide, dan patuhi aturan yang
                                            berlaku di destinasi.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Booking Card -->
                        <div class="booking-card">
                            <div class="price-section">
                                <div class="price">
                                    <span class="price-label">Mulai dari</span>
                                    <span
                                        class="price-amount"><?php echo formatCurrency($destination['price']); ?></span>
                                    <span class="price-unit">per orang</span>
                                </div>
                            </div>

                            <div class="destination-specs">
                                <div class="spec-item">
                                    <span class="spec-icon">⏱️</span>
                                    <div class="spec-info">
                                        <span class="spec-label">Durasi</span>
                                        <span class="spec-value"><?php echo $destination['duration']; ?></span>
                                    </div>
                                </div>

                                <div class="spec-item">
                                    <span class="spec-icon">👥</span>
                                    <div class="spec-info">
                                        <span class="spec-label">Kapasitas</span>
                                        <span class="spec-value">Max <?php echo $destination['max_capacity']; ?>
                                            orang</span>
                                    </div>
                                </div>

                                <div class="spec-item">
                                    <span class="spec-icon">🏷️</span>
                                    <div class="spec-info">
                                        <span class="spec-label">Kategori</span>
                                        <span class="spec-value"><?php echo $destination['category_name']; ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="booking-actions">
                                <?php if (isLoggedIn()): ?>
                                <?php if (isAdmin()): ?>
                                <a href="../admin/destinations.php?action=edit&id=<?php echo $destination['id']; ?>"
                                    class="btn btn-primary btn-full">Edit Destinasi</a>
                                <?php else: ?>
                                <a href="booking.php?destination_id=<?php echo $destination['id']; ?>"
                                    class="btn btn-primary btn-full">Book Sekarang</a>
                                <?php endif; ?>
                                <?php else: ?>
                                <a href="../login.php" class="btn btn-primary btn-full">Login untuk Book</a>
                                <?php endif; ?>

                                <div class="booking-note">
                                    <small>💡 Booking dapat dibatalkan hingga 24 jam sebelum tanggal travel</small>
                                </div>
                            </div>

                            <div class="contact-info">
                                <h4>Butuh Bantuan?</h4>
                                <div class="contact-item">
                                    <span>📞</span>
                                    <span>+62 21 1234 5678</span>
                                </div>
                                <div class="contact-item">
                                    <span>💬</span>
                                    <span>+62 812 3456 7890</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Related Destinations -->
            <?php if (!empty($related_destinations)): ?>
            <section class="related-destinations">
                <div class="container">
                    <h2>Destinasi Serupa</h2>
                    <div class="related-grid">
                        <?php foreach($related_destinations as $related): ?>
                        <div class="related-card">
                            <div class="related-image">
                                <img src="../assets/images/<?php echo $related['image'] ? $related['image'] : 'placeholder.jpg'; ?>"
                                    alt="<?php echo $related['name']; ?>">
                            </div>
                            <div class="related-content">
                                <h3><?php echo $related['name']; ?></h3>
                                <p class="related-location">📍 <?php echo $related['location']; ?></p>
                                <div class="related-price">
                                    <span><?php echo formatCurrency($related['price']); ?></span>
                                </div>
                                <a href="destination-detail.php?id=<?php echo $related['id']; ?>"
                                    class="btn btn-outline">Lihat Detail</a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
            <?php endif; ?>
        </main>

        <!-- Footer -->
        <footer class="detail-footer">
            <div class="container">
                <p>&copy; 2024 GoVista.com. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <style>
    .destination-detail-layout {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .detail-header {
        background: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 100;
    }

    .detail-navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .nav-brand a {
        text-decoration: none;
        color: #667eea;
    }

    .nav-menu {
        display: flex;
        list-style: none;
        gap: 2rem;
        margin: 0;
        padding: 0;
    }

    .nav-menu a {
        text-decoration: none;
        color: #2c3e50;
        font-weight: 500;
        transition: color 0.3s;
    }

    .nav-menu a:hover {
        color: #667eea;
    }

    .detail-main {
        flex: 1;
    }

    .breadcrumb {
        padding: 1rem 2rem;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .breadcrumb a {
        color: #667eea;
        text-decoration: none;
    }

    .breadcrumb span {
        color: #666;
    }

    .destination-hero {
        position: relative;
        height: 60vh;
        min-height: 400px;
    }

    .hero-image {
        width: 100%;
        height: 100%;
        position: relative;
    }

    .hero-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .hero-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: white;
        text-align: center;
    }

    .hero-overlay h1 {
        font-size: 3rem;
        margin: 0;
    }

    .hero-overlay p {
        font-size: 1.5rem;
    }

    .hero-overlay .btn {
        margin-top: 1rem;
    }

    .destination-detail {
        padding: 2rem;
    }

    .destination-title {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }

    .destination-description {
        font-size: 1.2rem;
        margin-bottom: 2rem;
    }

    .destination-images {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .destination-images img {
        width: 100%;
        height: auto;
        border-radius: 8px;
    }

    .destination-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
    }

    .destination-info .info-item {
        flex: 1;
        padding: 1rem;
        background: #f8f8f8;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .destination-info .info-item h4 {
        margin-bottom: 0.5rem;
    }

    .destination-info .info-item p {
        margin: 0;
    }

    .related-destinations {
        margin-top: 4rem;
    }

    .related-destinations h2 {
        margin-bottom: 2rem;
    }

    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1rem;
    }

    .related-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .related-image img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .related-content {
        padding: 1rem;
    }

    .related-content h3 {
        margin: 0.5rem 0;
    }

    .related-content .related-location {
        font-size: 0.9rem;
        color: #666;
    }

    .related-content .related-price {
        font-size: 1.2rem;
        font-weight: bold;
        margin: 0.5rem 0;
    }

    .related-content .btn {
        margin-top: 0.5rem;
    }

    .detail-footer {
        padding: 1rem 0;
        background: #f8f8f8;
        text-align: center;
    }

    .detail-footer p {
        margin: 0;
    }

</style>