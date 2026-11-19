// Admin / Common Scripts
document.addEventListener('DOMContentLoaded', () => {
    // Carousel Logic
    const track = document.querySelector('.carousel-track');
    const slides = Array.from(document.querySelectorAll('.carousel-slide'));
    const nextBtn = document.querySelector('.next-btn');
    const prevBtn = document.querySelector('.prev-btn');

    if (track && slides.length > 0) {
        let currentIndex = 0;

        const updateCarousel = () => {
            track.style.transform = `translateX(-${currentIndex * 100}%)`;
        };

        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                currentIndex = (currentIndex + 1) % slides.length;
                updateCarousel();
            });
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                currentIndex = (currentIndex - 1 + slides.length) % slides.length;
                updateCarousel();
            });
        }

        // Touch Swipe Navigation for Mobile
        let touchStartX = 0;
        let touchEndX = 0;
        track.addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });
        track.addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            if (touchStartX - touchEndX > 45) {
                currentIndex = (currentIndex + 1) % slides.length;
                updateCarousel();
            } else if (touchEndX - touchStartX > 45) {
                currentIndex = (currentIndex - 1 + slides.length) % slides.length;
                updateCarousel();
            }
        }, { passive: true });

        // Auto-advance
        setInterval(() => {
            currentIndex = (currentIndex + 1) % slides.length;
            updateCarousel();
        }, 5000);
    }
});

// HEADER DROPDOWN INTERACTIONS
document.addEventListener("DOMContentLoaded", function() {
    const searchToggle = document.getElementById("search-icon-toggle");
    const searchDropdown = document.getElementById("search-dropdown");
    
    const authToggle = document.getElementById("auth-icon-toggle");
    const authDropdown = document.getElementById("auth-dropdown");
    const authCloseBtn = document.getElementById("auth-close-btn");

    if (searchToggle && searchDropdown) {
        searchToggle.addEventListener("click", function(e) {
            e.preventDefault();
            searchDropdown.classList.toggle("active");
            if(authDropdown) authDropdown.classList.remove("active"); // close auth if open
            if(searchDropdown.classList.contains("active")) {
                const searchInput = searchDropdown.querySelector('input');
                if (searchInput) searchInput.focus();
            }
        });
    }

    if (authToggle && authDropdown) {
        authToggle.addEventListener("click", function(e) {
            e.preventDefault();
            authDropdown.classList.toggle("active");
            if(searchDropdown) searchDropdown.classList.remove("active"); // close search if open
        });
        
        if (authCloseBtn) {
            authCloseBtn.addEventListener("click", function(e) {
                e.preventDefault();
                authDropdown.classList.remove("active");
            });
        }
    }
    
    // Close when clicking outside
    document.addEventListener("click", function(e) {
        if (searchToggle && searchDropdown && !searchToggle.contains(e.target) && !searchDropdown.contains(e.target)) {
            searchDropdown.classList.remove("active");
        }
        if (authToggle && authDropdown && !authToggle.contains(e.target) && !authDropdown.contains(e.target)) {
            authDropdown.classList.remove("active");
        }
    });

    // Mobile Nav Drawer Toggle
    const mobileNavToggleBtn = document.getElementById("mobileNavToggleBtn");
    const mobileNavCloseBtn = document.getElementById("mobileNavCloseBtn");
    const mobileNavBackdrop = document.getElementById("mobileNavBackdrop");
    const mainNav = document.getElementById("storefrontMainNav");

    function openMobileNav() {
        if (mainNav) mainNav.classList.add("active");
        if (mobileNavBackdrop) mobileNavBackdrop.classList.add("active");
        document.body.classList.add("mobile-menu-open");
    }

    function closeMobileNav() {
        if (mainNav) mainNav.classList.remove("active");
        if (mobileNavBackdrop) mobileNavBackdrop.classList.remove("active");
        document.body.classList.remove("mobile-menu-open");
    }

    if (mobileNavToggleBtn) {
        mobileNavToggleBtn.addEventListener("click", function(e) {
            e.preventDefault();
            openMobileNav();
        });
    }

    if (mobileNavCloseBtn) {
        mobileNavCloseBtn.addEventListener("click", function(e) {
            e.preventDefault();
            closeMobileNav();
        });
    }

    if (mobileNavBackdrop) {
        mobileNavBackdrop.addEventListener("click", closeMobileNav);
    }

    // Keyboard ESC to close mobile nav
    document.addEventListener("keydown", function(e) {
        if (e.key === "Escape") {
            closeMobileNav();
            if (searchDropdown) searchDropdown.classList.remove("active");
            if (authDropdown) authDropdown.classList.remove("active");
        }
    });

    // Mobile Nav Submenu Accordion Toggles
    document.querySelectorAll(".main-nav .mobile-submenu-toggle").forEach(function(btn) {
        btn.addEventListener("click", function(e) {
            e.preventDefault();
            e.stopPropagation();
            const parentItem = this.closest(".nav-item");
            if (parentItem) {
                const isOpen = parentItem.classList.toggle("submenu-open");
                this.setAttribute("aria-expanded", isOpen ? "true" : "false");
            }
        });
    });

    // Close mobile nav on resize to desktop
    window.addEventListener("resize", function() {
        if (window.innerWidth > 1024) {
            closeMobileNav();
        }
    });

    // Desktop Nav Subcategories Hover Trigger
    document.querySelectorAll(".nav-menu-categories > li.has-dropdown").forEach(function(item) {
        const dropMenu = item.querySelector(".dropdown-menu");
        if (!dropMenu) return;

        item.addEventListener("mouseenter", function() {
            if (window.innerWidth > 991) {
                dropMenu.style.setProperty("display", "block", "important");
                dropMenu.style.setProperty("opacity", "1", "important");
                dropMenu.style.setProperty("visibility", "visible", "important");
                dropMenu.style.setProperty("pointer-events", "auto", "important");
            }
        });

        item.addEventListener("mouseleave", function() {
            if (window.innerWidth > 991) {
                dropMenu.style.removeProperty("display");
                dropMenu.style.removeProperty("opacity");
                dropMenu.style.removeProperty("visibility");
                dropMenu.style.removeProperty("pointer-events");
            }
        });
    });

    const menuToggle = document.getElementById("menuToggle");
    if (menuToggle) {
        menuToggle.addEventListener('click', function() {
            const menuItems = document.getElementsByClassName('menu-item');
            for (let i = 0; i < menuItems.length; i++) {
                menuItems[i].classList.toggle("hidden");
            }
        });
    }
});

// Product Detail Page Zoom & Thumbnails
document.addEventListener("DOMContentLoaded", function() {
    window.changeImage = function(element, url) {
        const mainImage = document.getElementById('main-image');
        if(mainImage) {
            mainImage.src = url;
            const thumbnails = document.querySelectorAll('.thumbnail');
            thumbnails.forEach(th => th.classList.remove('active'));
            element.classList.add('active');
        }
    };
    const container = document.getElementById('product-top-image-box') || document.getElementById('main-image-container');
    const img = document.getElementById('main-image');
    if (container && img) {
        container.addEventListener('mousemove', function(e) {
            const { left, top, width, height } = container.getBoundingClientRect();
            const x = (e.clientX - left) / width * 100;
            const y = (e.clientY - top) / height * 100;
            img.style.transformOrigin = `${x}% ${y}%`;
        });
        container.addEventListener('mouseleave', function() {
            img.style.transformOrigin = 'center center';
        });
    }
});

// Floating Scroll-To-Top & Testimonials Rotation
function handleScrollBtnVisibility() {
    const scrollBtn = document.getElementById('scrollToTopBtn');
    if (scrollBtn) {
        const scrolled = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
        if (scrolled > 200) {
            scrollBtn.style.setProperty('display', 'flex', 'important');
        } else {
            scrollBtn.style.setProperty('display', 'none', 'important');
        }
    }
}
window.addEventListener('scroll', handleScrollBtnVisibility, { passive: true });
window.addEventListener('resize', handleScrollBtnVisibility, { passive: true });

document.addEventListener('DOMContentLoaded', function() {
    handleScrollBtnVisibility();
    const scrollBtn = document.getElementById('scrollToTopBtn');
    if (scrollBtn) {
        const scrollToTopAction = function(e) {
            if (e) e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        };
        scrollBtn.addEventListener('click', scrollToTopAction);
        scrollBtn.addEventListener('touchend', scrollToTopAction);
    }

    const testimonialSlides = document.querySelectorAll('.testimonial-slide');
    if (testimonialSlides.length > 1) {
        let currentSlide = 0;
        setInterval(() => {
            testimonialSlides[currentSlide].style.opacity = '0';
            testimonialSlides[currentSlide].style.pointerEvents = 'none';
            
            currentSlide = (currentSlide + 1) % testimonialSlides.length;
            
            testimonialSlides[currentSlide].style.opacity = '1';
            testimonialSlides[currentSlide].style.pointerEvents = 'auto';
        }, 10000);
    }

});

