// Browser Compatibility Check
(function() {
    'use strict';

    // Polyfill for Array.from (IE 11)
    if (!Array.from) {
        Array.from = function(arrayLike) {
            return Array.prototype.slice.call(arrayLike);
        };
    }

    // Polyfill for Object.assign (IE 11)
    if (typeof Object.assign !== 'function') {
        Object.assign = function(target) {
            if (target == null) {
                throw new TypeError('Cannot convert undefined or null to object');
            }
            var to = Object(target);
            for (var index = 1; index < arguments.length; index++) {
                var nextSource = arguments[index];
                if (nextSource != null) {
                    for (var nextKey in nextSource) {
                        if (Object.prototype.hasOwnProperty.call(nextSource, nextKey)) {
                            to[nextKey] = nextSource[nextKey];
                        }
                    }
                }
            }
            return to;
        };
    }

    // Polyfill for fetch API (older browsers)
    if (!window.fetch) {
        console.warn('Fetch API not supported. Please upgrade your browser.');
    }
})();

// Image preview functionality with cross-browser support
window.previewImages = function(input) {
    var preview = document.getElementById('image-preview');
    if (!preview) return;

    preview.innerHTML = '';

    if (input.files) {
        // Use Array.from for better compatibility
        var files = Array.from ? Array.from(input.files) : Array.prototype.slice.call(input.files);

        files.forEach(function(file, index) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = '<img src="' + e.target.result + '" class="w-full h-32 object-cover rounded-lg">' +
                    '<button type="button" onclick="removeImage(' + index + ')" ' +
                    'class="absolute top-1 right-1 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">' +
                    '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>' +
                    '</svg></button>';
                preview.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }
};

// Mobile menu toggle
window.toggleMobileMenu = function() {
    const menu = document.getElementById('mobile-menu');
    if (menu) {
        menu.classList.toggle('hidden');
    }
};

// Confirm delete
window.confirmDelete = function(message) {
    return confirm(message || 'Are you sure you want to delete this item?');
};

// Flash message auto-hide
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert-dismissible');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});

// Search filter toggle
window.toggleFilters = function() {
    const filters = document.getElementById('search-filters');
    if (filters) {
        filters.classList.toggle('hidden');
    }
};

console.log('CharyMeld Adverts app.js loaded');
