// Initialize all interactive components
document.addEventListener('DOMContentLoaded', function() {
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Animate numbers on scroll
    const animateNumbers = () => {
        const numbers = document.querySelectorAll('.animate-number');
        numbers.forEach(number => {
            const updateCount = () => {
                const target = +number.getAttribute('data-target');
                const count = +number.innerText;
                const increment = target / 200;
                if (count < target) {
                    number.innerText = Math.ceil(count + increment);
                    setTimeout(updateCount, 1);
                } else {
                    number.innerText = target;
                }
            };
            updateCount();
        });
    };
    
    // Intersection Observer for animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                if (entry.target.classList.contains('animate-number')) {
                    animateNumbers();
                }
            }
        });
    }, observerOptions);
    
    // Observe elements
    document.querySelectorAll('.animate-on-scroll').forEach(el => {
        observer.observe(el);
    });
    
    // Interactive tooltips
    const tooltips = document.querySelectorAll('[data-tooltip]');
    tooltips.forEach(tooltip => {
        tooltip.addEventListener('mouseenter', function() {
            const tooltipText = this.getAttribute('data-tooltip');
            const tooltipEl = document.createElement('div');
            tooltipEl.className = 'tooltip-popup';
            tooltipEl.textContent = tooltipText;
            this.appendChild(tooltipEl);
        });
        
        tooltip.addEventListener('mouseleave', function() {
            const tooltipEl = this.querySelector('.tooltip-popup');
            if (tooltipEl) {
                tooltipEl.remove();
            }
        });
    });
    
    // Print functionality
    const printButton = document.getElementById('printInfo');
    if (printButton) {
        printButton.addEventListener('click', function() {
            window.print();
        });
    }
    
    // Share functionality
    const shareButton = document.getElementById('shareInfo');
    if (shareButton) {
        shareButton.addEventListener('click', async function() {
            if (navigator.share) {
                try {
                    await navigator.share({
                        title: 'Informasi SPK Siswa Teladan - SDIT As Sunnah',
                        text: 'Pelajari sistem penilaian siswa teladan yang objektif dan transparan',
                        url: window.location.href
                    });
                } catch (err) {
                    console.log('Error sharing:', err);
                }
            } else {
                // Fallback - copy to clipboard
                navigator.clipboard.writeText(window.location.href);
                alert('Link telah disalin ke clipboard!');
            }
        });
    }
});