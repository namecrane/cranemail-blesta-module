/*
 * NameCrane Mail client pages. Password fields ([data-ncm-password]) start hidden, with a
 * show/hide button and (on forms) a generate button; a generated password is shown so it can
 * be noted. Passwords mix lower case, upper case, digits and symbols, as the mail server wants.
 * A form with data-ncm-confirm asks before it's sent.
 */
(function () {
    'use strict';

    var sets = ['abcdefghjkmnpqrstuvwxyz', 'ABCDEFGHJKMNPQRSTUVWXYZ', '23456789', '!#$%&()*+-.;<=>?@^_~'];

    function random(max) {
        var value = new Uint32Array(1);
        window.crypto.getRandomValues(value);
        return value[0] % max;
    }

    function generate(length) {
        var all = sets.join('');
        var chars = sets.map(function (set) { return set.charAt(random(set.length)); });

        while (chars.length < length) {
            chars.push(all.charAt(random(all.length)));
        }

        for (var i = chars.length - 1; i > 0; i--) {
            var j = random(i + 1);
            var swap = chars[i];
            chars[i] = chars[j];
            chars[j] = swap;
        }

        return chars.join('');
    }

    function reveal(field, visible) {
        var toggle = field.querySelector('[data-ncm-toggle]');
        var label = toggle.getAttribute(visible ? 'data-hide-label' : 'data-show-label');
        field.querySelector('input').type = visible ? 'text' : 'password';
        toggle.setAttribute('aria-pressed', visible ? 'true' : 'false');
        toggle.setAttribute('title', label);
        toggle.setAttribute('aria-label', label);
    }

    document.addEventListener('click', function (event) {
        var button = event.target.closest('[data-ncm-generate], [data-ncm-toggle]');
        var field = button && button.closest('[data-ncm-password]');

        if (!field) {
            return;
        }

        var input = field.querySelector('input');

        if (button.hasAttribute('data-ncm-generate')) {
            input.value = generate(16);
            reveal(field, true);
            input.focus();
            input.select();
        } else {
            reveal(field, input.type === 'password');
        }
    });

    document.addEventListener('submit', function (event) {
        var message = event.target.getAttribute && event.target.getAttribute('data-ncm-confirm');

        if (message && !window.confirm(message)) {
            event.preventDefault();
        }
    });
})();
