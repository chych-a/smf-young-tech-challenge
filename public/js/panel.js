document.addEventListener('DOMContentLoaded', () => {
    const shell = document.querySelector('#app-shell');
    const sidebarToggle = document.querySelector('[data-sidebar-toggle]');
    const sidebarState = window.localStorage.getItem('smf-sidebar-collapsed');

    if (shell && sidebarState === '1') {
        shell.classList.add('is-sidebar-collapsed');
    }

    sidebarToggle?.addEventListener('click', () => {
        shell?.classList.toggle('is-sidebar-collapsed');
        window.localStorage.setItem('smf-sidebar-collapsed', shell?.classList.contains('is-sidebar-collapsed') ? '1' : '0');
    });

    document.querySelectorAll('[data-file-input]').forEach((input) => {
        const target = document.querySelector(input.dataset.fileInput);
        input.addEventListener('change', () => {
            if (!target) return;
            target.textContent = input.files && input.files.length ? input.files[0].name : 'Nie wybrano pliku';
        });
    });

    document.querySelectorAll('[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            const message = form.getAttribute('data-confirm') || 'Czy na pewno?';
            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });

    const statusButton = document.querySelector('[data-status-toggle]');
    const statusPopover = document.querySelector('[data-status-popover]');

    statusButton?.addEventListener('click', (event) => {
        event.stopPropagation();
        statusPopover?.classList.toggle('is-open');
    });

    document.addEventListener('click', (event) => {
        if (!statusPopover?.contains(event.target) && !statusButton?.contains(event.target)) {
            statusPopover?.classList.remove('is-open');
        }
    });

    const commandPalette = document.querySelector('[data-command-palette]');
    const commandInput = document.querySelector('[data-command-input]');
    const commandOpenButtons = document.querySelectorAll('[data-command-open]');
    const commandItems = document.querySelectorAll('[data-command-item]');
    const productModal = document.querySelector('[data-product-modal]');

    const openLayer = (layer, focusTarget = null) => {
        if (!layer) return;
        layer.hidden = false;
        document.body.style.overflow = 'hidden';
        setTimeout(() => focusTarget?.focus(), 0);
    };

    const closeLayers = () => {
        if (commandPalette) commandPalette.hidden = true;
        if (productModal) productModal.hidden = true;
        document.body.style.overflow = '';
    };

    const openCommandPalette = () => {
        openLayer(commandPalette, commandInput);
        if (commandInput) {
            commandInput.value = '';
            commandItems.forEach((item) => item.hidden = false);
        }
    };

    const openProductModal = () => {
        closeLayers();
        openLayer(productModal, document.querySelector('#quick_name'));
    };

    commandOpenButtons.forEach((button) => button.addEventListener('click', openCommandPalette));
    document.querySelectorAll('[data-product-modal-open]').forEach((button) => button.addEventListener('click', openProductModal));
    document.querySelectorAll('[data-modal-close]').forEach((button) => button.addEventListener('click', closeLayers));

    [commandPalette, productModal].forEach((layer) => {
        layer?.addEventListener('click', (event) => {
            if (event.target === layer) closeLayers();
        });
    });

    commandInput?.addEventListener('input', () => {
        const query = commandInput.value.trim().toLowerCase();
        commandItems.forEach((item) => {
            const text = `${item.textContent} ${item.dataset.keywords || ''}`.toLowerCase();
            item.hidden = query.length > 0 && !text.includes(query);
        });
    });

    document.addEventListener('keydown', (event) => {
        const target = event.target;
        const isTyping = target instanceof HTMLInputElement || target instanceof HTMLTextAreaElement || target instanceof HTMLSelectElement || target?.isContentEditable;

        if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
            event.preventDefault();
            openCommandPalette();
            return;
        }

        if (event.key === 'Escape') {
            closeLayers();
            return;
        }

        if (!isTyping && event.key.toLowerCase() === 'n') {
            event.preventDefault();
            openProductModal();
        }
    });

    document.querySelectorAll('[data-bulk-scope]').forEach((scope) => {
        const checkboxes = scope.querySelectorAll('[data-bulk-checkbox]');
        const master = scope.querySelector('[data-bulk-master]');
        const count = scope.querySelector('[data-bulk-count]');
        const submit = document.querySelector(scope.dataset.bulkSubmit || '');

        const refresh = () => {
            const selected = [...checkboxes].filter((checkbox) => checkbox.checked).length;
            if (count) count.textContent = selected.toString();
            if (submit) submit.disabled = selected === 0;
            if (master) {
                master.checked = selected > 0 && selected === checkboxes.length;
                master.indeterminate = selected > 0 && selected < checkboxes.length;
            }
        };

        master?.addEventListener('change', () => {
            checkboxes.forEach((checkbox) => checkbox.checked = master.checked);
            refresh();
        });

        checkboxes.forEach((checkbox) => checkbox.addEventListener('change', refresh));
        refresh();
    });
});
