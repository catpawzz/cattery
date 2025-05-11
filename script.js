document.addEventListener('DOMContentLoaded', function() {
    const galleryItems = document.querySelectorAll('.gallery-item');
    
    galleryItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            const img = this.querySelector('img');
            if (img) {
                img.style.transform = 'scale(1.05)';
            }
        });
        
        item.addEventListener('mouseleave', function() {
            const img = this.querySelector('img');
            if (img) {
                img.style.transform = 'scale(1)';
            }
        });
    });
});

function shareImage() {
    const url = window.location.href;
    
    if (navigator.share) {
        navigator.share({
            title: document.title,
            url: url
        })
        .catch(error => {
            copyToClipboard(url);
        });
    } else {
        copyToClipboard(url);
    }
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text)
        .then(() => {
            const shareBtn = document.querySelector('.share-btn');
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

document.addEventListener('keydown', function(e) {
    const prevBtn = document.querySelector('.prev-btn');
    const nextBtn = document.querySelector('.next-btn');
    
    if (e.key === 'ArrowLeft' && prevBtn) {
        prevBtn.click();
    } else if (e.key === 'ArrowRight' && nextBtn) {
        nextBtn.click();
    } else if (e.key === 'Escape') {
        const backBtn = document.querySelector('.back-btn');
        if (backBtn) backBtn.click();
    }
});
