document.addEventListener('DOMContentLoaded', () => {
    const swiper = new Swiper('.testimonials-swiper', {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        autoplay: {
            delay: 4000,
            disableOnInteraction: false, // keeps autoplay after manual slide
        },
        breakpoints: {
            768: { slidesPerView: 1 },
            1024: { slidesPerView: 2 }
        },
    });

    // Fade-in animation
    const boxes = document.querySelectorAll('.testimonial-box');
    function reveal() {
        const windowHeight = window.innerHeight;
        boxes.forEach(box => {
            const boxTop = box.getBoundingClientRect().top;
            if (boxTop < windowHeight - 100) {
                box.classList.add('show');
            }
        });
    }
    window.addEventListener('scroll', reveal);
    window.addEventListener('load', reveal);

    const contact = document.querySelector('.contact');

    function revealContact() {
        const trigger = window.innerHeight - 100;
        if (contact.getBoundingClientRect().top < trigger) {
            contact.classList.add('show');
        }
    }

    window.addEventListener('scroll', revealContact);
    window.addEventListener('load', revealContact);

    
    // =============================
    // NOTIFICATION SYSTEM
    // =============================
    function showNotification(message, type = "success") {
        const container = document.getElementById("notification-container");

        if (!container) return;

        const notification = document.createElement("div");
        notification.classList.add("notification", type);
        notification.innerText = message;

        container.appendChild(notification);

        // Trigger animation
        setTimeout(() => {
            notification.classList.add("show");
        }, 100);

        // Auto remove
        setTimeout(() => {
            notification.classList.remove("show");

            setTimeout(() => {
                notification.remove();
            }, 400);
        }, 3000);
    }
    // =============================
    // BALANCE ANIMATION FUNCTION
    // =============================
    function animateBalance(element, start, end, duration = 800) {
        let startTime = null;
    
        function animate(currentTime) {
            if (!startTime) startTime = currentTime;
    
            let progress = Math.min((currentTime - startTime) / duration, 1);
            let value = start + (end - start) * progress;
    
            element.innerText = "KES " + value.toLocaleString(undefined, {
                minimumFractionDigits: 2
            });
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        }
    
        requestAnimationFrame(animate);
    }
    
});


// ======== SEARCH FUNCTION =========
function filterTable(tableId, value) {
    const filter = value.toLowerCase();
    const rows = document.querySelectorAll(`#${tableId} tbody tr`);

    rows.forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(filter) ? '' : 'none';
    });
}

// ======== MODAL =========
function openModal(url, message) {
    document.getElementById('confirmModal').style.display = 'block';
    document.getElementById('modalMessage').innerText = message;
    document.getElementById('confirmBtn').href = url;
}

function closeModal() {
    document.getElementById('confirmModal').style.display = 'none';
}