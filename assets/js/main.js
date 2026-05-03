document.addEventListener('DOMContentLoaded', function () {
    var menuToggle = document.querySelector('.menu-toggle');
    var menu = document.querySelector('#main-menu');

    if (menuToggle && menu) {
        menuToggle.addEventListener('click', function () {
            menu.classList.toggle('open');
        });
    }

    var toastContainer = document.querySelector('[data-toasts]');
    if (toastContainer) {
        var toasts = toastContainer.querySelectorAll('.toast');

        toasts.forEach(function (toast) {
            var closeBtn = toast.querySelector('.toast-close');
            var closeTimeout = null;
            var fadeTimeout = null;

            function clearTimers() {
                if (fadeTimeout) { window.clearTimeout(fadeTimeout); fadeTimeout = null; }
                if (closeTimeout) { window.clearTimeout(closeTimeout); closeTimeout = null; }
            }

            function schedule() {
                clearTimers();
                toast.classList.remove('is-closing');
                // 5 seconds visible, then start fading; fully gone at 10 seconds.
                fadeTimeout = window.setTimeout(function () {
                    toast.classList.add('is-closing');
                }, 5000);
                closeTimeout = window.setTimeout(function () {
                    toast.remove();
                    if (!toastContainer.querySelector('.toast')) {
                        toastContainer.remove();
                    }
                }, 10000);
            }

            function closeNow() {
                clearTimers();
                toast.remove();
                if (!toastContainer.querySelector('.toast')) {
                    toastContainer.remove();
                }
            }

            if (closeBtn) {
                closeBtn.addEventListener('click', closeNow);
            }

            toast.addEventListener('mouseenter', function () { schedule(); });
            toast.addEventListener('mouseleave', function () { schedule(); });

            schedule();
        });
    }

    var benefitsSection = document.querySelector('.client-benefits');
    if (benefitsSection) {
        var tiles = benefitsSection.querySelectorAll('.benefit-tile');
        var titleEl = benefitsSection.querySelector('.benefit-details-title');
        var textEl = benefitsSection.querySelector('.benefit-details-text');

        function applyBenefitFromTile(tile) {
            if (!tile || !titleEl || !textEl) {
                return;
            }
            tiles.forEach(function (t) { t.classList.remove('is-active'); });
            tile.classList.add('is-active');
            titleEl.textContent = tile.getAttribute('data-benefit-title') || tile.querySelector('h3')?.textContent || 'Что получает клиент';
            textEl.textContent = tile.getAttribute('data-benefit-text') || '';
        }

        tiles.forEach(function (tile) {
            tile.addEventListener('mouseenter', function () { applyBenefitFromTile(tile); });
            tile.addEventListener('focus', function () { applyBenefitFromTile(tile); });
            tile.addEventListener('click', function () { applyBenefitFromTile(tile); });
        });
    }

    var fileInputs = document.querySelectorAll('.file-input input[type="file"]');
    fileInputs.forEach(function (input) {
        var wrapper = input.closest('.file-input');
        var nameEl = wrapper ? wrapper.querySelector('[data-file-name]') : null;
        if (!nameEl) {
            return;
        }
        input.addEventListener('change', function () {
            var fileName = input.files && input.files[0] ? input.files[0].name : 'Файл не выбран';
            nameEl.textContent = fileName;
        });
    });

    var cropper = document.querySelector('[data-cropper]');
    if (cropper) {
        var stage = cropper.querySelector('[data-crop-stage]');
        var img = cropper.querySelector('[data-crop-img]');
        var zoom = cropper.querySelector('[data-crop-zoom]');
        var file = cropper.querySelector('[data-crop-file]');
        var form = document.querySelector('[data-crop-form]');
        var out = document.querySelector('[data-cropped-image]');

        var state = {
            naturalW: 0,
            naturalH: 0,
            baseScale: 1,
            zoom: 1,
            x: 0,
            y: 0,
            dragging: false,
            startX: 0,
            startY: 0,
            originX: 0,
            originY: 0,
        };

        function clamp(n, min, max) {
            return Math.max(min, Math.min(max, n));
        }

        function computeBaseScale() {
            if (!stage || !img || !state.naturalW || !state.naturalH) {
                return 1;
            }
            var rect = stage.getBoundingClientRect();
            var sx = rect.width / state.naturalW;
            var sy = rect.height / state.naturalH;
            // cover (so no empty borders)
            return Math.max(sx, sy);
        }

        function applyTransform() {
            if (!img) return;
            img.style.transform = 'translate(-50%, -50%) translate(' + state.x + 'px,' + state.y + 'px) scale(' + (state.baseScale * state.zoom) + ')';
        }

        function onImageReady() {
            state.naturalW = img.naturalWidth || 0;
            state.naturalH = img.naturalHeight || 0;
            state.baseScale = computeBaseScale();
            state.zoom = zoom ? parseFloat(zoom.value || '1') : 1;
            state.x = 0;
            state.y = 0;
            applyTransform();
        }

        if (img && img.complete) {
            onImageReady();
        } else if (img) {
            img.addEventListener('load', onImageReady);
        }

        if (zoom) {
            zoom.addEventListener('input', function () {
                state.zoom = parseFloat(zoom.value || '1');
                applyTransform();
            });
        }

        if (stage && img) {
            stage.addEventListener('mousedown', function (e) {
                state.dragging = true;
                state.startX = e.clientX;
                state.startY = e.clientY;
                state.originX = state.x;
                state.originY = state.y;
            });

            window.addEventListener('mousemove', function (e) {
                if (!state.dragging) return;
                state.x = state.originX + (e.clientX - state.startX);
                state.y = state.originY + (e.clientY - state.startY);
                applyTransform();
            });

            window.addEventListener('mouseup', function () {
                state.dragging = false;
            });
        }

        if (file && img) {
            file.addEventListener('change', function () {
                var f = file.files && file.files[0] ? file.files[0] : null;
                if (!f) return;
                var url = URL.createObjectURL(f);
                img.src = url;
                if (out) out.value = '';
            });
        }

        function exportCroppedPng() {
            if (!stage || !img || !state.naturalW || !state.naturalH) {
                return '';
            }

            var rect = stage.getBoundingClientRect();
            var canvasW = 800;
            var canvasH = Math.round(canvasW * (rect.height / rect.width));
            var canvas = document.createElement('canvas');
            canvas.width = canvasW;
            canvas.height = canvasH;
            var ctx = canvas.getContext('2d');
            if (!ctx) return '';

            var renderScale = state.baseScale * state.zoom;
            var renderW = state.naturalW * renderScale;
            var renderH = state.naturalH * renderScale;

            var stageW = rect.width;
            var stageH = rect.height;

            var imgLeft = stageW / 2 - renderW / 2 + state.x;
            var imgTop = stageH / 2 - renderH / 2 + state.y;

            var sx = (0 - imgLeft) / renderW * state.naturalW;
            var sy = (0 - imgTop) / renderH * state.naturalH;
            var sw = stageW / renderW * state.naturalW;
            var sh = stageH / renderH * state.naturalH;

            // clamp inside source image
            sx = clamp(sx, 0, state.naturalW);
            sy = clamp(sy, 0, state.naturalH);
            sw = clamp(sw, 1, state.naturalW - sx);
            sh = clamp(sh, 1, state.naturalH - sy);

            ctx.drawImage(img, sx, sy, sw, sh, 0, 0, canvasW, canvasH);
            return canvas.toDataURL('image/png', 0.92);
        }

        if (form && out) {
            form.addEventListener('submit', function () {
                // only export if user chose a new file (or moved zoom/drag) – safe to always export when stage exists
                out.value = exportCroppedPng();
            });
        }
    }

    var links = document.querySelectorAll('[onclick*="confirm"]');
    links.forEach(function (link) {
        link.addEventListener('keydown', function (event) {
            if (event.key === 'Enter') {
                link.click();
            }
        });
    });

    var phoneInputs = document.querySelectorAll('[data-phone-mask]');
    phoneInputs.forEach(function (input) {
        function formatPhone(value) {
            var digits = value.replace(/\D/g, '');
            if (digits.charAt(0) === '8') {
                digits = '7' + digits.slice(1);
            }
            if (digits.charAt(0) !== '7') {
                digits = '7' + digits;
            }
            digits = digits.slice(0, 11);

            var local = digits.slice(1);
            var part1 = local.slice(0, 3);
            var part2 = local.slice(3, 6);
            var part3 = local.slice(6, 8);
            var part4 = local.slice(8, 10);

            var formatted = '+7';
            if (part1) {
                formatted += ' (' + part1;
            }
            if (part1.length === 3) {
                formatted += ')';
            }
            if (part2) {
                formatted += '-' + part2;
            }
            if (part3) {
                formatted += '-' + part3;
            }
            if (part4) {
                formatted += ' ' + part4;
            }
            return formatted;
        }

        input.addEventListener('input', function () {
            input.value = formatPhone(input.value);
        });

        input.addEventListener('focus', function () {
            if (!input.value) {
                input.value = '+7';
            }
        });
    });
});
