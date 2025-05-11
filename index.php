<?php
$dir = 'pictures/';
$allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$images = [];

if (is_dir($dir)) {
    $files = scandir($dir);
    foreach ($files as $file) {
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if (in_array($ext, $allowed_types)) {
            $images[] = $file;
        }
    }
}

function generateMeowText() {
    $meows = ['Meow', 'Purr', 'Mrow', 'Mrrp', 'Prrr', 'Miau', 'Nyaa', 'Mew'];
    $length = rand(1, 3);
    $text = '';
    
    for ($i = 0; $i < $length; $i++) {
        $text .= $meows[array_rand($meows)] . ' ';
    }
    
    return trim($text);
}

$current_img = isset($_GET['img']) ? $_GET['img'] : '';
$current_index = array_search($current_img, $images);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#1e1e2e">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <title>Cattery - Yumi's Gallery</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .lightbox {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(30, 30, 46, 0.95);
            backdrop-filter: blur(10px);
            z-index: 1000;
            overflow: hidden;
            touch-action: pan-y pinch-zoom;
        }
        
        .lightbox.active {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        
        .lightbox-content {
            position: relative;
            width: 95%;
            max-width: 1200px;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            background: rgba(30, 30, 46, 0.5);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            border: 1px solid rgba(203, 166, 247, 0.15);
            box-shadow: 0 10px 40px rgba(17, 17, 27, 0.4);
            padding: min(2rem, 5vw);
        }
        
        .lightbox-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .lightbox-title {
            color: var(--ctp-mocha-text);
            font-size: clamp(0.9rem, 3vw, 1.2rem);
            margin: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            flex: 1;
            text-align: center;
        }
        
        .lightbox-close {
            padding: min(0.6rem, 3vw) min(1.2rem, 5vw);
            background: rgba(180, 190, 254, 0.1);
            color: var(--ctp-mocha-blue);
            border-radius: 10px;
            cursor: pointer;
            border: 1px solid rgba(180, 190, 254, 0.2);
            transition: all 0.2s ease;
            z-index: 1010;
            backdrop-filter: blur(5px);
            font-weight: 600;
        }
        
        .lightbox-close:hover, .lightbox-close:active {
            background: rgba(180, 190, 254, 0.2);
            border-color: rgba(180, 190, 254, 0.3);
            transform: translateY(-2px);
        }
        
        .lightbox-img-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
            max-height: 65vh;
            margin: 0 auto;
            position: relative;
            overflow: hidden;
        }
        
        .lightbox-img {
            max-width: 100%;
            max-height: 65vh;
            object-fit: contain;
            border-radius: 10px;
            transition: transform 0.3s ease;
            user-select: none;
            -webkit-user-drag: none;
        }
        
        .lightbox-img.zoomed {
            transform: scale(1.5);
            cursor: zoom-out;
        }
        
        .lightbox-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
            width: 100%;
        }
        
        .lightbox-nav-btn {
            padding: min(0.6rem, 3vw) min(1.2rem, 5vw);
            background: rgba(180, 190, 254, 0.1);
            color: var(--ctp-mocha-blue);
            border-radius: 10px;
            border: 1px solid rgba(180, 190, 254, 0.2);
            cursor: pointer;
            transition: all 0.2s ease;
            backdrop-filter: blur(5px);
            font-weight: 600;
            flex: 1;
            max-width: 120px;
            text-align: center;
        }
        
        .lightbox-nav-btn:hover, .lightbox-nav-btn:active {
            background: rgba(180, 190, 254, 0.2);
            border-color: rgba(180, 190, 254, 0.3);
            transform: translateY(-2px);
        }
        
        .lightbox-share {
            padding: min(0.6rem, 3vw) min(1.2rem, 5vw);
            background: rgba(180, 190, 254, 0.1);
            color: var(--ctp-mocha-blue);
            border-radius: 10px;
            border: 1px solid rgba(180, 190, 254, 0.2);
            cursor: pointer;
            transition: all 0.2s ease;
            backdrop-filter: blur(5px);
            font-weight: 600;
            z-index: 1010;
        }
        
        .lightbox-share:hover, .lightbox-share:active {
            background: rgba(180, 190, 254, 0.2);
            border-color: rgba(180, 190, 254, 0.3);
            transform: translateY(-2px);
        }

        .lightbox-counter {
            color: var(--ctp-mocha-subtext0);
            font-size: 0.9rem;
            text-align: center;
            flex: 1;
        }
        
        .placeholder {
            background: rgba(30, 30, 46, 0.5);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
            transition: opacity 0.3s ease;
        }
        
        .spinner {
            width: 30px;
            height: 30px;
            border: 3px solid rgba(203, 166, 247, 0.3);
            border-radius: 50%;
            border-top-color: rgba(203, 166, 247, 0.9);
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .gallery-item img {
            opacity: 0;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }
        
        .gallery-item img.loaded {
            opacity: 1;
        }
        
        @media (max-width: 768px) {
            .lightbox-content {
                padding: 1rem;
                width: 95%;
                border-radius: 15px;
            }
            
            .lightbox-header {
                margin-bottom: 0.8rem;
            }
            
            .lightbox-close, .lightbox-share, .lightbox-nav-btn {
                padding: 0.4rem 0.8rem;
                font-size: 0.9rem;
            }
            
            .lightbox-img-container {
                max-height: 60vh;
            }
            
            .lightbox-img {
                max-height: 60vh;
            }
            
            .lightbox-nav {
                margin-top: 0.8rem;
            }
        }
        
        @media (max-width: 480px) {
            .lightbox-content {
                padding: 0.8rem;
                width: 98%;
            }
            
            .lightbox-title {
                font-size: 0.9rem;
                max-width: 50%;
            }
            
            .lightbox-nav-btn {
                max-width: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Cattery</h1>
            <p>A futuristic gallery featuring Yumi the cat</p>
        </header>
        
        <div class="gallery">
            <?php if (empty($images)): ?>
                <div class="empty-gallery">No images found in the pictures folder</div>
            <?php else: ?>
                <?php foreach ($images as $index => $image): ?>
                    <a href="javascript:void(0)" class="gallery-item" data-img="<?= htmlspecialchars($image) ?>" data-index="<?= $index ?>">
                        <div class="glass-card">
                            <div class="placeholder"><div class="spinner"></div></div>
                            <img data-src="<?= $dir . $image ?>" alt="Yumi" class="lazy-image">
                            <div class="image-overlay">
                                <span><?= generateMeowText() ?></span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="lightbox" id="lightbox">
        <div class="swipe-indicator swipe-left">&lt;</div>
        <div class="swipe-indicator swipe-right">&gt;</div>
        <div class="lightbox-content">
            <div class="lightbox-header">
                <div class="lightbox-share" onclick="shareImage()">Share</div>
                <h2 class="lightbox-title"></h2>
                <div class="lightbox-close" onclick="closeLightbox()">Close</div>
            </div>
            <div class="lightbox-img-container">
                <div class="loading-spinner" id="lightboxLoader"></div>
                <img src="" alt="Yumi" class="lightbox-img" id="lightboxImg">
            </div>
            <div class="lightbox-nav">
                <div class="lightbox-nav-btn lightbox-prev" onclick="navigateImage(-1)">Previous</div>
                <div class="lightbox-counter"><span id="currentImageIndex">0</span>/<span id="totalImages">0</span></div>
                <div class="lightbox-nav-btn lightbox-next" onclick="navigateImage(1)">Next</div>
            </div>
        </div>
    </div>
    
    <script>
        const lightbox = document.getElementById('lightbox');
        const lightboxImg = document.querySelector('.lightbox-img');
        const lightboxTitle = document.querySelector('.lightbox-title');
        const galleryItems = document.querySelectorAll('.gallery-item');
        const images = <?= json_encode($images) ?>;
        const imageDir = '<?= $dir ?>';
        const currentImageIndex = document.getElementById('currentImageIndex');
        const totalImages = document.getElementById('totalImages');
        const lightboxLoader = document.getElementById('lightboxLoader');
        let currentIndex = <?= $current_index !== false ? $current_index : -1 ?>;
        let touchStartX = 0;
        let touchEndX = 0;
        let lastTapTime = 0;
        let isZoomed = false;
        let loadedImages = new Set();
        
        totalImages.textContent = images.length;
        
        function lazyLoadImages() {
            const options = {
                root: null,
                rootMargin: '0px',
                threshold: 0.1
            };
            
            const lazyImageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        const src = img.getAttribute('data-src');
                        
                        if (!src) return;
                        
                        const tmpImg = new Image();
                        tmpImg.src = src;
                        tmpImg.onload = function() {
                            img.src = src;
                            img.classList.add('loaded');
                            const placeholder = img.parentNode.querySelector('.placeholder');
                            if (placeholder) {
                                placeholder.style.opacity = '0';
                                setTimeout(() => {
                                    placeholder.style.display = 'none';
                                }, 300);
                            }
                            observer.unobserve(img);
                        };
                    }
                });
            }, options);
            
            const lazyImages = document.querySelectorAll('.lazy-image');
            lazyImages.forEach(image => {
                lazyImageObserver.observe(image);
            });
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            lazyLoadImages();
        });
        
        function openLightbox(index) {
            if (index < 0 || index >= images.length) return;
            
            currentIndex = index;
            currentImageIndex.textContent = currentIndex + 1;
            
            lightboxLoader.style.display = 'block';
            lightboxImg.style.opacity = '0';
            
            const image = images[index];
            const imagePath = imageDir + image;
            
            if (loadedImages.has(imagePath)) {
                lightboxImg.src = imagePath;
            } else {
                lightboxImg.src = '';
                const tmpImg = new Image();
                tmpImg.onload = function() {
                    lightboxImg.src = imagePath;
                    loadedImages.add(imagePath);
                };
                tmpImg.src = imagePath;
            }
            
            lightboxTitle.textContent = generateMeowText();
            lightbox.classList.add('active');
            
            const url = new URL(window.location);
            url.searchParams.set('img', image);
            history.pushState({}, '', url);
            
            document.body.style.overflow = 'hidden';
            
            preloadAdjacentImages(index);
        }
        
        function closeLightbox() {
            lightbox.classList.remove('active');
            
            const url = new URL(window.location);
            url.searchParams.delete('img');
            history.pushState({}, '', url);
            
            isZoomed = false;
            lightboxImg.classList.remove('zoomed');
            
            document.body.style.overflow = '';
        }
        
        function navigateImage(step) {
            let newIndex = (currentIndex + step + images.length) % images.length;
            openLightbox(newIndex);
            
            const indicator = step > 0 ? document.querySelector('.swipe-right') : document.querySelector('.swipe-left');
            indicator.classList.add('visible');
            setTimeout(() => {
                indicator.classList.remove('visible');
            }, 300);
        }
        
        function generateMeowText() {
            const meows = ['Meow', 'Purr', 'Mrow', 'Mrrp', 'Prrr', 'Miau', 'Nyaa', 'Mew'];
            const length = Math.floor(Math.random() * 3) + 1;
            let text = '';
            
            for (let i = 0; i < length; i++) {
                text += meows[Math.floor(Math.random() * meows.length)] + ' ';
            }
            
            return text.trim();
        }
        
        function shareImage() {
            const url = window.location.href;
            
            if (navigator.share) {
                navigator.share({
                    title: document.title,
                    url: url
                }).catch(error => {
                    copyToClipboard(url);
                });
            } else {
                copyToClipboard(url);
            }
        }
        
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text)
                .then(() => {
                    const shareBtn = document.querySelector('.lightbox-share');
                    const originalText = shareBtn.textContent;
                    shareBtn.textContent = 'Copied!';
                    
                    setTimeout(() => {
                        shareBtn.textContent = originalText;
                    }, 2000);
                })
                .catch(err => {
                    console.error('Could not copy text: ', err);
                });
        }
        
        function toggleZoom(event) {
            if (!isZoomed) {
                lightboxImg.classList.add('zoomed');
                
                if (event && event.type === 'click') {
                    // Calculate position to zoom to
                    const x = event.clientX;
                    const y = event.clientY;
                    const imgRect = lightboxImg.getBoundingClientRect();
                    const offsetX = ((x - imgRect.left) / imgRect.width) * 100;
                    const offsetY = ((y - imgRect.top) / imgRect.height) * 100;
                    
                    lightboxImg.style.transformOrigin = `${offsetX}% ${offsetY}%`;
                }
            } else {
                lightboxImg.classList.remove('zoomed');
                lightboxImg.style.transformOrigin = 'center center';
            }
            
            isZoomed = !isZoomed;
        }
        
        function handleTouchStart(event) {
            touchStartX = event.touches[0].clientX;
            
            // Check for double tap
            const currentTime = new Date().getTime();
            const tapLength = currentTime - lastTapTime;
            
            if (tapLength < 300 && tapLength > 0) {
                // Double tap detected
                event.preventDefault();
                toggleZoom(event);
            }
            
            lastTapTime = currentTime;
        }
        
        function handleTouchMove(event) {
            if (isZoomed) return; // Don't swipe when zoomed
            touchEndX = event.touches[0].clientX;
        }
        
        function handleTouchEnd() {
            if (isZoomed) return; // Don't swipe when zoomed
            
            const swipeThreshold = 80;
            if (touchStartX - touchEndX > swipeThreshold) {
                // Swipe left, go to next image
                navigateImage(1);
            } else if (touchEndX - touchStartX > swipeThreshold) {
                // Swipe right, go to previous image
                navigateImage(-1);
            }
        }
        
        function preloadImage(index) {
            if (index < 0 || index >= images.length) return;
            
            const imagePath = imageDir + images[index];
            if (loadedImages.has(imagePath)) return;
            
            const img = new Image();
            img.onload = function() {
                loadedImages.add(imagePath);
            };
            img.src = imagePath;
        }
        
        function preloadAdjacentImages(currentIdx) {
            setTimeout(() => {
                preloadImage(currentIdx + 1);
                preloadImage(currentIdx - 1);
            }, 100);
        }
        
        lightboxImg.onload = function() {
            lightboxLoader.style.display = 'none';
            lightboxImg.style.opacity = '1';
        };
        
        lightboxImg.onerror = function() {
            lightboxLoader.style.display = 'none';
            lightboxImg.style.opacity = '1';
            lightboxImg.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgZmlsbD0iIzFmMWYyZSIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBmb250LXNpemU9IjI0IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBhbGlnbm1lbnQtYmFzZWxpbmU9Im1pZGRsZSIgZmlsbD0iI2NkZDZmNCI+SW1hZ2UgbG9hZCBlcnJvcjwvdGV4dD48L3N2Zz4=';
        };
        
        if (currentIndex !== false && currentIndex >= 0) {
            openLightbox(currentIndex);
        }
        
        galleryItems.forEach(item => {
            item.addEventListener('click', function() {
                const index = parseInt(this.getAttribute('data-index'));
                openLightbox(index);
            });
        });
        
        lightbox.addEventListener('touchstart', handleTouchStart, {passive: false});
        lightbox.addEventListener('touchmove', handleTouchMove, {passive: true});
        lightbox.addEventListener('touchend', handleTouchEnd, {passive: true});
        
        lightboxImg.addEventListener('click', toggleZoom);
        
        document.addEventListener('keydown', function(e) {
            if (!lightbox.classList.contains('active')) return;
            
            if (e.key === 'ArrowLeft') {
                navigateImage(-1);
            } else if (e.key === 'ArrowRight') {
                navigateImage(1);
            } else if (e.key === 'Escape') {
                closeLightbox();
            }
        });
        
        window.addEventListener('scroll', function() {
            if (window.scrollY > 100) {
                const nextBatch = 10;
                for (let i = 0; i < nextBatch; i++) {
                    preloadImage((currentIndex + i + 1) % images.length);
                }
            }
        });
    </script>
</body>
</html>
