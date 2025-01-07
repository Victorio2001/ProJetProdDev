function toggleModal() {
    const modal = document.getElementById('modal-overlay');
    const modalData = document.getElementById('modal-data');

    modal.classList.toggle('active');
    modalData.classList.toggle('active');
}
//Responsive du menu hamburger
function toggleMobileMenu() {
    const mobileMenu = document.getElementById('mobile-menu');

    mobileMenu.classList.toggle('hidden');
}

document.addEventListener("DOMContentLoaded", function () {
    const mobileMenuButton = document.querySelector('[aria-controls="mobile-menu"]');
    const mobileMenu = document.getElementById('mobile-menu');

    mobileMenu.classList.add('hidden');
    mobileMenuButton.addEventListener('click', toggleMobileMenu);
});

//Responsive du menu click sur l'icon
function toggleUserMenu() {
    const userMenu = document.getElementById('user-menu');
    userMenu.classList.toggle('hidden');
}

document.addEventListener("DOMContentLoaded", function () {
    const currentPageUrl = window.location.href;
    const navLinks = document.querySelectorAll('.nav-link');

    navLinks.forEach(function(navLink) {
        const linkUrl = navLink.href;
        if (linkUrl === currentPageUrl) {
            navLink.setAttribute('aria-current', 'page');
            navLink.classList.add('bg-gray-900', 'text-white');
            navLink.classList.remove('text-gray-300');
        }
        else {
            navLink.removeAttribute('aria-current');
            navLink.classList.add('text-gray-300');
            navLink.classList.remove('bg-gray-900', 'text-white');
        }
    });

    const mobileMenuButton = document.querySelector('[aria-controls="mobile-menu"]');
    const mobileMenu = document.getElementById('mobile-menu');

    mobileMenu.classList.add('hidden');
    mobileMenuButton.addEventListener('click', toggleMobileMenu);
});



