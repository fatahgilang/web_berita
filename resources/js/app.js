// Handle image fallbacks
document.addEventListener('DOMContentLoaded', function() {
    // Set background images for banners
    document.querySelectorAll('.banner-bg').forEach(function(element) {
        const thumbnail = element.getAttribute('data-thumbnail');
        if (thumbnail) {
            element.style.backgroundImage = 'url(\'' + thumbnail + '\')';
        }
    });

    // Handle image fallbacks
    document.querySelectorAll('.author-img').forEach(function(img) {
        img.addEventListener('error', function() {
            this.src = '/img/profile.svg';
        });
    });
});