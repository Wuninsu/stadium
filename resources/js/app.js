import 'bootstrap/dist/js/bootstrap.bundle.min.js';

import Chart from 'chart.js/auto';

window.Chart = Chart;

/**
 * Aliu Mahama Sports Stadium — shared admin/public behaviour.
 * Ported verbatim from public/project-ui/*.html (previously duplicated
 * inline in every reference page) so every Blade page shares one copy.
 */
function initStadiumUi() {
    /* ---------------- Sidebar collapse (desktop) + off-canvas (mobile) ---------------- */
    var sidebar = document.getElementById('appSidebar');
    var toggleBtn = document.getElementById('sidebarToggle');
    var backdrop = document.getElementById('sidebarBackdrop');

    function isMobile() { return window.innerWidth < 992; }

    if (toggleBtn && sidebar && !toggleBtn.dataset.bound) {
        toggleBtn.dataset.bound = '1';
        toggleBtn.addEventListener('click', function () {
            if (isMobile()) {
                sidebar.classList.toggle('mobile-open');
                if (backdrop) backdrop.classList.toggle('show');
            } else {
                sidebar.classList.toggle('collapsed');
            }
        });
    }
    if (backdrop && !backdrop.dataset.bound) {
        backdrop.dataset.bound = '1';
        backdrop.addEventListener('click', function () {
            sidebar.classList.remove('mobile-open');
            backdrop.classList.remove('show');
        });
    }

    /* ---------------- Public navbar toggler icon swap ---------------- */
    var navToggler = document.querySelector('.navbar-toggler');
    if (navToggler && !navToggler.dataset.bound) {
        navToggler.dataset.bound = '1';
        var navTarget = document.querySelector(navToggler.getAttribute('data-bs-target'));
        if (navTarget) {
            navTarget.addEventListener('shown.bs.collapse', function () {
                var icon = navToggler.querySelector('i');
                if (icon) icon.className = 'bi bi-x-lg';
            });
            navTarget.addEventListener('hidden.bs.collapse', function () {
                var icon = navToggler.querySelector('i');
                if (icon) icon.className = 'bi bi-list fs-3';
            });
        }
    }

    /* ---------------- Animated scoreboard / stat counters ---------------- */
    function animateCount(el) {
        var target = parseFloat(el.getAttribute('data-count'));
        var suffix = el.getAttribute('data-suffix') || '';
        var decimals = el.getAttribute('data-decimals') ? parseInt(el.getAttribute('data-decimals'), 10) : 0;
        var duration = 1200;
        var start = null;

        function step(ts) {
            if (!start) start = ts;
            var progress = Math.min((ts - start) / duration, 1);
            var eased = 1 - Math.pow(1 - progress, 3);
            var value = target * eased;
            el.textContent = value.toFixed(decimals).replace(/\B(?=(\d{3})+(?!\d))/g, ',') + suffix;
            if (progress < 1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
    }

    var counters = document.querySelectorAll('[data-count]:not([data-counted])');
    if (counters.length) {
        var obs = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.setAttribute('data-counted', '1');
                    animateCount(entry.target);
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.4 });
        counters.forEach(function (c) { obs.observe(c); });
    }

    /* ---------------- OTP input auto-advance ---------------- */
    var otpInputs = document.querySelectorAll('.otp-input');
    if (otpInputs.length) {
        otpInputs.forEach(function (input, idx) {
            if (input.dataset.bound) return;
            input.dataset.bound = '1';
            input.addEventListener('input', function () {
                input.value = input.value.replace(/[^0-9]/g, '').slice(0, 1);
                if (input.value && otpInputs[idx + 1]) otpInputs[idx + 1].focus();
            });
            input.addEventListener('keydown', function (e) {
                if (e.key === 'Backspace' && !input.value && otpInputs[idx - 1]) {
                    otpInputs[idx - 1].focus();
                }
            });
        });
    }

    /* ---------------- Password strength meter ---------------- */
    var pwInput = document.getElementById('newPassword');
    var pwBar = document.getElementById('pwStrengthBar');
    var pwLabel = document.getElementById('pwStrengthLabel');
    if (pwInput && pwBar && !pwInput.dataset.bound) {
        pwInput.dataset.bound = '1';
        pwInput.addEventListener('input', function () {
            var val = pwInput.value;
            var score = 0;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;
            var pct = [8, 35, 65, 85, 100][score] || 0;
            var colors = ['#D64545', '#D64545', '#E6A800', '#16A075', '#0B6E4F'];
            var labels = ['Too short', 'Weak', 'Fair', 'Good', 'Strong'];
            pwBar.style.width = pct + '%';
            pwBar.style.background = colors[score];
            if (pwLabel) pwLabel.textContent = val ? labels[score] : '';
        });
    }

    /* ---------------- Password visibility toggle ---------------- */
    document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
        if (btn.dataset.bound) return;
        btn.dataset.bound = '1';
        btn.addEventListener('click', function () {
            var target = document.querySelector(btn.getAttribute('data-toggle-password'));
            if (!target) return;
            var icon = btn.querySelector('i');
            if (target.type === 'password') {
                target.type = 'text';
                if (icon) icon.className = 'bi bi-eye-slash';
            } else {
                target.type = 'password';
                if (icon) icon.className = 'bi bi-eye';
            }
        });
    });
}

if (window.Chart) {
    Chart.defaults.font.family = "'Inter', sans-serif";
    Chart.defaults.color = '#4B615A';
    Chart.defaults.plugins.legend.labels.usePointStyle = true;
    Chart.defaults.plugins.legend.labels.boxWidth = 8;
    Chart.defaults.plugins.legend.labels.boxHeight = 8;
}

window.StadiumTheme = {
    pitch700: '#0B6E4F',
    pitch500: '#16A075',
    pitch100: '#E3F3EC',
    flood400: '#FFC72C',
    flood600: '#E6A800',
    ink600: '#4B615A',
    line: '#DDE7E0',
};

document.addEventListener('DOMContentLoaded', initStadiumUi);
document.addEventListener('livewire:navigated', initStadiumUi);
window.initStadiumUi = initStadiumUi;

document.addEventListener('livewire:init', function () {
    Livewire.on('show-modal', function (data) {
        var id = (data && data.id) ? data.id : data;
        var el = document.getElementById(id);
        if (el && window.bootstrap) {
            window.bootstrap.Modal.getOrCreateInstance(el).show();
        }
    });
    Livewire.on('hide-modal', function (data) {
        var id = (data && data.id) ? data.id : data;
        var el = document.getElementById(id);
        if (el && window.bootstrap) {
            var instance = window.bootstrap.Modal.getInstance(el);
            if (instance) instance.hide();
        }
    });
});
