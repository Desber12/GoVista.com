<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Travel Website - Jelajahi Keindahan Indonesia</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php
    require_once 'includes/config.php';
    require_once 'includes/functions.php';
    
    // Get featured destinations
    $destinations = getDestinations($conn, 6);
    $categories = getCategories($conn);
    ?>
    
    <!-- Header -->
    <header class="header">
        <nav class="navbar">
            <div class="nav-brand">
                <h1>GoVista.com</h1>
            </div>
            <ul class="nav-menu">
                <li><a href="#home">Beranda</a></li>
                <li><a href="#destinations">Destinasi</a></li>
                <li><a href="#about">Tentang</a></li>
                <li><a href="#contact">Kontak</a></li>
                <?php if (isLoggedIn()): ?>
                    <li><a href="<?php echo isAdmin() ? 'admin/dashboard.php' : 'user/dashboard.php'; ?>">Dashboard</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Daftar</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    
    <!-- Hero Section -->
    <section id="home" class="hero">
        <div class="hero-content">
            <h1>Jelajahi Keindahan Indonesia</h1>
            <p>Temukan destinasi wisata terbaik dengan paket tour yang menarik dan terpercaya</p>
            <?php if (!isLoggedIn()): ?>
                <a href="register.php" class="btn btn-primary">Mulai Petualangan</a>
            <?php endif; ?>
        </div>
    </section>
    
    <!-- Destinations Section -->
    <section id="destinations" class="destinations">
        <div class="container">
            <h2>Destinasi Populer</h2>
            
            <!-- Category Filter -->
            <div class="category-filter">
                <button class="filter-btn active" data-category="all">Semua</button>
                <?php foreach ($categories as $category): ?>
                    <button class="filter-btn" data-category="<?php echo $category['id']; ?>">
                        <?php echo $category['name']; ?>
                    </button>
                <?php endforeach; ?>
            </div>
            
            <!-- Destinations Grid -->
            <div class="destinations-grid">
                <?php foreach ($destinations as $destination): ?>
                    <div class="destination-card" data-category="<?php echo $destination['category_id']; ?>">
                        <div class="card-image">
                            <img src="assets/images/<?php echo $destination['image'] ? $destination['image'] : 'placeholder.jpg'; ?>" 
                                 alt="<?php echo $destination['name']; ?>">
                            <div class="card-overlay">
                                <span class="category-tag"><?php echo $destination['category_name']; ?></span>
                            </div>
                        </div>
                        <div class="card-content">
                            <h3><?php echo $destination['name']; ?></h3>
                            <p class="location"><?php echo $destination['location']; ?></p>
                            <p class="description"><?php echo substr($destination['description'], 0, 100) . '...'; ?></p>
                            <div class="card-footer">
                                <div class="price">
                                    <span class="price-label">Mulai dari</span>
                                    <span class="price-amount"><?php echo formatCurrency($destination['price']); ?></span>
                                </div>
                                <div class="duration"><?php echo $destination['duration']; ?></div>
                            </div>
                            <?php if (isLoggedIn() && !isAdmin()): ?>
                                <a href="user/booking.php?destination=<?php echo $destination['id']; ?>" class="btn btn-primary">Book Now</a>
                            <?php elseif (!isLoggedIn()): ?>
                                <a href="login.php" class="btn btn-primary">Login untuk Book</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    
    <!-- About Section -->
    <section id="about" class="about">
        <div class="container">
            <div class="about-content">
                <div class="about-text">
                    <h2>Tentang GoVista.com</h2>
                    <p>GoVista.com adalah platform travel terpercaya yang menyediakan berbagai paket wisata menarik di seluruh Indonesia. Kami berkomitmen untuk memberikan pengalaman liburan yang tak terlupakan dengan pelayanan terbaik.</p>
                    <div class="features">
                        <div class="feature">
                            <h4>Destinasi Terpilih</h4>
                            <p>Destinasi wisata pilihan dengan pemandangan yang menakjubkan</p>
                        </div>
                        <div class="feature">
                            <h4>Harga Terjangkau</h4>
                            <p>Paket wisata dengan harga yang kompetitif dan terjangkau</p>
                        </div>
                        <div class="feature">
                            <h4>Pelayanan 24/7</h4>
                            <p>Tim customer service yang siap membantu kapan saja</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Contact Section -->
    <section id="contact" class="contact">
        <div class="container">
            <h2>Hubungi Kami</h2>
            <div class="contact-info">
                <div class="contact-item">
                    <h4>Alamat</h4>
                    <p>Kampung susuk, Abdul Hakim</p>
                </div>
                <div class="contact-item">
                    <h4>Telepon</h4>
                    <p>08979402811</p>
                </div>
                <div class="contact-item">
                    <h4>Email</h4>
                    <p>info@GoVista.com.com</p>
                </div>
                <div class="contact-item">
                    <h4>Jam Operasional</h4>
                    <p>Senin - Minggu: 08:00 - 22:00 WIB</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 GoVista.com. All rights reserved.</p>
        </div>
    </footer>
    
    <script>
        // Category filter functionality
        document.addEventListener('DOMContentLoaded', function() {
            const filterBtns = document.querySelectorAll('.filter-btn');
            const destinationCards = document.querySelectorAll('.destination-card');
            
            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    // Remove active class from all buttons
                    filterBtns.forEach(b => b.classList.remove('active'));
                    // Add active class to clicked button
                    this.classList.add('active');
                    
                    const category = this.getAttribute('data-category');
                    
                    destinationCards.forEach(card => {
                        if (category === 'all' || card.getAttribute('data-category') === category) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>

