import './bootstrap';

const imageInputs = document.querySelectorAll('[data-preview]');
imageInputs.forEach((input) => {
    const previewSelector = input.getAttribute('data-preview');
    if (!previewSelector) {
        return;
    }

    const preview = document.querySelector(previewSelector);
    if (!preview) {
        return;
    }

    const placeholder = preview.innerHTML;

    input.addEventListener('change', () => {
        const [file] = input.files || [];
        if (!file) {
            preview.innerHTML = placeholder;
            return;
        }

        if (!file.type.startsWith('image/')) {
            preview.innerHTML = placeholder;
            return;
        }

        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.alt = 'Room photo preview';

        preview.innerHTML = '';
        preview.appendChild(img);
    });
});
