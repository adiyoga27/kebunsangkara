const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
const finePointer = window.matchMedia('(pointer: fine)').matches;

/**
 * Reveal elements as they scroll into view. Children of [data-reveal-stagger] get an increasing delay.
 */
function initReveal() {
    document.querySelectorAll('[data-reveal-stagger]').forEach((group) => {
        const step = Number(group.dataset.revealStagger) || 120;
        group.querySelectorAll(':scope > [data-reveal]').forEach((child, index) => {
            child.style.setProperty('--reveal-delay', `${index * step}ms`);
        });
    });

    const elements = document.querySelectorAll('[data-reveal]');

    if (!('IntersectionObserver' in window)) {
        elements.forEach((element) => element.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.15, rootMargin: '0px 0px -40px 0px' },
    );

    elements.forEach((element) => observer.observe(element));
}

/**
 * Turn the header solid once the visitor scrolls past the top of the page.
 */
function initHeader() {
    const header = document.querySelector('[data-header]');
    if (!header) {
        return;
    }

    const update = () => header.toggleAttribute('data-scrolled', window.scrollY > 24);
    update();
    window.addEventListener('scroll', update, { passive: true });
}

/**
 * Animate numbers from 0 to their [data-count-to] value when they become visible.
 */
function initCounters() {
    const counters = document.querySelectorAll('[data-count-to]');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) {
                return;
            }

            const element = entry.target;
            const target = Number(element.dataset.countTo);
            const suffix = element.dataset.countSuffix ?? '';
            const duration = 1800;
            const start = performance.now();

            const tick = (now) => {
                const progress = Math.min((now - start) / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 4);
                element.textContent = Math.round(target * eased).toLocaleString('id-ID') + suffix;
                if (progress < 1) {
                    requestAnimationFrame(tick);
                }
            };

            requestAnimationFrame(tick);
            observer.unobserve(element);
        });
    });

    counters.forEach((counter) => {
        counter.textContent = '0' + (counter.dataset.countSuffix ?? '');
        observer.observe(counter);
    });
}

/**
 * Subtle 3D tilt with a moving glare on cards marked [data-tilt].
 */
function initTilt() {
    document.querySelectorAll('[data-tilt]').forEach((card) => {
        const strength = Number(card.dataset.tilt) || 8;

        card.addEventListener('pointermove', (event) => {
            const rect = card.getBoundingClientRect();
            const x = (event.clientX - rect.left) / rect.width;
            const y = (event.clientY - rect.top) / rect.height;
            card.style.setProperty('--ry', `${(x - 0.5) * strength}deg`);
            card.style.setProperty('--rx', `${(0.5 - y) * strength}deg`);
            card.style.setProperty('--gx', `${x * 100}%`);
            card.style.setProperty('--gy', `${y * 100}%`);
        });

        card.addEventListener('pointerleave', () => {
            card.style.setProperty('--rx', '0deg');
            card.style.setProperty('--ry', '0deg');
        });
    });
}

/**
 * Cursor spotlight and mouse parallax for elements inside [data-spotlight].
 */
function initSpotlight() {
    document.querySelectorAll('[data-spotlight]').forEach((section) => {
        const layers = section.querySelectorAll('[data-depth]');

        section.addEventListener('pointermove', (event) => {
            const rect = section.getBoundingClientRect();
            const x = event.clientX - rect.left;
            const y = event.clientY - rect.top;
            section.style.setProperty('--mx', `${x}px`);
            section.style.setProperty('--my', `${y}px`);

            const offsetX = x / rect.width - 0.5;
            const offsetY = y / rect.height - 0.5;
            layers.forEach((layer) => {
                const depth = Number(layer.dataset.depth);
                layer.style.translate = `${offsetX * depth}px ${offsetY * depth}px`;
            });
        });
    });
}

/**
 * Scroll-linked vertical parallax for elements marked [data-parallax] (speed factor).
 */
function initParallax() {
    const elements = [...document.querySelectorAll('[data-parallax]')];
    if (elements.length === 0) {
        return;
    }

    let ticking = false;
    const update = () => {
        const viewportCenter = window.innerHeight / 2;
        elements.forEach((element) => {
            const rect = element.getBoundingClientRect();
            const distance = rect.top + rect.height / 2 - viewportCenter;
            element.style.transform = `translate3d(0, ${distance * -Number(element.dataset.parallax)}px, 0)`;
        });
        ticking = false;
    };

    window.addEventListener(
        'scroll',
        () => {
            if (!ticking) {
                requestAnimationFrame(update);
                ticking = true;
            }
        },
        { passive: true },
    );
    update();
}

/**
 * Keep only one FAQ item open at a time.
 */
function initAccordions() {
    document.querySelectorAll('[data-accordion]').forEach((group) => {
        const items = group.querySelectorAll('details');
        items.forEach((item) => {
            item.addEventListener('toggle', () => {
                if (item.open) {
                    items.forEach((other) => other !== item && (other.open = false));
                }
            });
        });
    });
}

/**
 * Header submenus: open on mouse hover, toggle on tap or keyboard, close on Escape or outside click.
 */
function initDropdowns() {
    const dropdowns = [...document.querySelectorAll('[data-dropdown]')];

    dropdowns.forEach((dropdown) => {
        const summary = dropdown.querySelector('summary');
        let pointerType = '';

        dropdown.addEventListener('pointerenter', (event) => {
            if (event.pointerType === 'mouse') {
                dropdown.open = true;
            }
        });
        dropdown.addEventListener('pointerleave', (event) => {
            if (event.pointerType === 'mouse') {
                dropdown.open = false;
            }
        });

        // Hover already opened the menu, so a mouse click must not toggle it closed again.
        summary.addEventListener('pointerdown', (event) => (pointerType = event.pointerType));
        summary.addEventListener('click', (event) => {
            if (event.detail > 0 && pointerType === 'mouse') {
                event.preventDefault();
            }
        });

        dropdown.addEventListener('focusout', (event) => {
            if (event.relatedTarget && !dropdown.contains(event.relatedTarget)) {
                dropdown.open = false;
            }
        });
        dropdown.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && dropdown.open) {
                dropdown.open = false;
                summary.focus();
            }
        });
    });

    document.addEventListener('click', (event) => {
        dropdowns.forEach((dropdown) => {
            if (!dropdown.contains(event.target)) {
                dropdown.open = false;
            }
        });
    });
}

initReveal();
initHeader();
initDropdowns();
initAccordions();

if (!reducedMotion) {
    initCounters();
    initParallax();

    if (finePointer) {
        initTilt();
        initSpotlight();
    }
} else {
    document.querySelectorAll('[data-count-to]').forEach((element) => {
        element.textContent = Number(element.dataset.countTo).toLocaleString('id-ID') + (element.dataset.countSuffix ?? '');
    });
}
