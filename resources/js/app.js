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

/**
 * Product photo slider: arrows and thumbnails drive a native scroll-snap track, which also supports swiping.
 */
function initSliders() {
    document.querySelectorAll('[data-slider]').forEach((slider) => {
        const track = slider.querySelector('[data-slider-track]');
        const thumbs = [...slider.querySelectorAll('[data-slider-thumb]')];
        const current = slider.querySelector('[data-slider-current]');
        const total = track.children.length;
        let index = 0;
        let target = null;

        const show = (next) => {
            index = target = (next + total) % total;
            track.scrollTo({ left: index * track.clientWidth, behavior: reducedMotion ? 'auto' : 'smooth' });
            update();
        };
        const update = () => {
            current.textContent = index + 1;
            thumbs.forEach((thumb, position) => thumb.setAttribute('aria-current', String(position === index)));
        };

        slider.querySelector('[data-slider-prev]').addEventListener('click', () => show(index - 1));
        slider.querySelector('[data-slider-next]').addEventListener('click', () => show(index + 1));
        thumbs.forEach((thumb, position) => thumb.addEventListener('click', () => show(position)));
        track.addEventListener('keydown', (event) => {
            if (event.key === 'ArrowLeft' || event.key === 'ArrowRight') {
                event.preventDefault();
                show(index + (event.key === 'ArrowRight' ? 1 : -1));
            }
        });

        let settle;
        track.addEventListener(
            'scroll',
            () => {
                clearTimeout(settle);
                settle = setTimeout(() => {
                    const position = Math.round(track.scrollLeft / track.clientWidth);
                    // A slow animation may pause before it reaches the photo that was clicked.
                    if (target !== null && position !== target) {
                        return;
                    }
                    target = null;
                    index = position;
                    update();
                }, 120);
            },
            { passive: true },
        );
        ['pointerdown', 'touchstart', 'wheel'].forEach((name) => track.addEventListener(name, () => (target = null), { passive: true }));
        window.addEventListener('resize', () => track.scrollTo({ left: index * track.clientWidth }));
    });
}

/**
 * Shrink photos in the browser before upload so several phone photos fit within the server upload limits.
 */
function initImageResize() {
    const maxSide = 1600;
    const maxBytes = 700 * 1024;

    const shrink = async (file) => {
        try {
            const bitmap = await createImageBitmap(file, { imageOrientation: 'from-image' });
            const scale = Math.min(1, maxSide / Math.max(bitmap.width, bitmap.height));
            if (scale === 1 && file.size <= maxBytes) {
                return file;
            }

            const canvas = document.createElement('canvas');
            canvas.width = Math.round(bitmap.width * scale);
            canvas.height = Math.round(bitmap.height * scale);
            const context = canvas.getContext('2d');
            context.fillStyle = '#fff';
            context.fillRect(0, 0, canvas.width, canvas.height);
            context.drawImage(bitmap, 0, 0, canvas.width, canvas.height);

            for (const quality of [0.85, 0.7, 0.55]) {
                const blob = await new Promise((resolve) => canvas.toBlob(resolve, 'image/jpeg', quality));
                if (blob && (blob.size <= maxBytes || quality === 0.55)) {
                    return blob.size < file.size ? new File([blob], file.name.replace(/\.\w+$/, '') + '.jpg', { type: 'image/jpeg' }) : file;
                }
            }
        } catch {
            // Unreadable images are sent as they are; the server reports the validation error.
        }

        return file;
    };

    document.querySelectorAll('[data-image-resize]').forEach((input) => {
        input.addEventListener('change', async () => {
            const status = input.closest('div').querySelector('[data-image-resize-status]');
            const submits = input.form?.querySelectorAll('button:not([type=button])') ?? [];

            submits.forEach((button) => (button.disabled = true));
            if (status) {
                status.textContent = 'Menyiapkan foto…';
            }

            const transfer = new DataTransfer();
            for (const file of input.files) {
                transfer.items.add(await shrink(file));
            }
            input.files = transfer.files;

            submits.forEach((button) => (button.disabled = false));
            if (status) {
                status.textContent = input.files.length ? `${input.files.length} foto siap diunggah.` : '';
            }
        });
    });
}

initReveal();
initHeader();
initDropdowns();
initAccordions();
initSliders();
initImageResize();

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
