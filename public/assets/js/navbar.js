// navbar.js — Toggle menu mobile

document.addEventListener('DOMContentLoaded', function () {
  const menuToggle = document.getElementById('menu-toggle');
  const navLinks   = document.getElementById('nav-links');
  const menuIcon   = document.getElementById('menu-icon');

  if (menuToggle && navLinks) {
    menuToggle.addEventListener('click', function () {
      navLinks.classList.toggle('open');

      // Ganti icon hamburger/close
      if (menuIcon) {
        if (navLinks.classList.contains('open')) {
          menuIcon.classList.remove('bi-list');
          menuIcon.classList.add('bi-x');
        } else {
          menuIcon.classList.remove('bi-x');
          menuIcon.classList.add('bi-list');
        }
      }
    });

    // Tutup menu saat klik link
    navLinks.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', () => {
        navLinks.classList.remove('open');
        if (menuIcon) {
          menuIcon.classList.remove('bi-x');
          menuIcon.classList.add('bi-list');
        }
      });
    });
  }
});