import './bootstrap';

const deleteDialog = document.querySelector('[data-delete-dialog]');

if (deleteDialog instanceof HTMLDialogElement) {
    const deleteItem = deleteDialog.querySelector('[data-delete-item]');
    const confirmDeleteButton = deleteDialog.querySelector('[data-delete-confirm]');
    let pendingDeleteForm;
    let pendingDeleteSubmitter;
    let confirmedDeleteForm;

    if (
        deleteItem instanceof HTMLElement
        && confirmDeleteButton instanceof HTMLButtonElement
    ) {
        document.addEventListener('submit', (event) => {
            if (!(event.target instanceof HTMLFormElement) || !event.target.matches('[data-delete-confirm]')) {
                return;
            }

            if (event.target === confirmedDeleteForm) {
                confirmedDeleteForm = undefined;
                return;
            }

            event.preventDefault();
            pendingDeleteForm = event.target;
            pendingDeleteSubmitter = event.submitter;
            deleteItem.textContent = pendingDeleteForm.dataset.deleteItem || 'cet élément';
            deleteDialog.showModal();
        });

        confirmDeleteButton.addEventListener('click', () => {
            if (!pendingDeleteForm) {
                return;
            }

            const form = pendingDeleteForm;
            const submitter = pendingDeleteSubmitter;
            pendingDeleteForm = undefined;
            pendingDeleteSubmitter = undefined;
            confirmedDeleteForm = form;
            deleteDialog.close();
            form.requestSubmit(submitter instanceof HTMLElement ? submitter : undefined);
        });

        deleteDialog.querySelector('[data-delete-cancel]')?.addEventListener('click', () => {
            deleteDialog.close();
        });

        deleteDialog.addEventListener('click', (event) => {
            if (event.target === deleteDialog) {
                deleteDialog.close();
            }
        });

        deleteDialog.addEventListener('close', () => {
            pendingDeleteForm = undefined;
            pendingDeleteSubmitter = undefined;
        });
    }
}

const loanErrorDialog = document.querySelector('[data-loan-error-dialog]');

if (loanErrorDialog instanceof HTMLDialogElement) {
    loanErrorDialog.showModal();

    loanErrorDialog.querySelector('[data-loan-error-close]')?.addEventListener('click', () => {
        loanErrorDialog.close();
    });

    loanErrorDialog.addEventListener('click', (event) => {
        if (event.target === loanErrorDialog) {
            loanErrorDialog.close();
        }
    });
}

const bookFilterForm = document.querySelector('[data-live-book-filters]');

if (bookFilterForm instanceof HTMLFormElement) {
    const searchInput = bookFilterForm.querySelector('input[name="q"]');
    const instantFilters = bookFilterForm.querySelectorAll('select, input[type="checkbox"]');
    let searchTimeout;

    searchInput?.addEventListener('input', () => {
        window.clearTimeout(searchTimeout);
        searchTimeout = window.setTimeout(() => {
            bookFilterForm.requestSubmit();
        }, 300);
    });

    instantFilters.forEach((filter) => {
        filter.addEventListener('change', () => {
            window.clearTimeout(searchTimeout);
            bookFilterForm.requestSubmit();
        });
    });
}

document.querySelectorAll('[data-search-select]').forEach((searchSelect) => {
    if (!(searchSelect instanceof HTMLElement)) {
        return;
    }

    const searchInput = searchSelect.querySelector('[data-search-input]');
    const valueInput = searchSelect.querySelector('[data-search-value]');
    const resultsList = searchSelect.querySelector('[data-search-results]');
    const status = searchSelect.querySelector('[data-search-status]');
    const searchUrl = searchSelect.dataset.searchUrl;
    const emptyMessage = searchSelect.dataset.searchEmpty;
    let searchTimeout;
    let activeRequest;
    let activeOptionIndex = -1;

    if (
        !(searchInput instanceof HTMLInputElement)
        || !(valueInput instanceof HTMLInputElement)
        || !(resultsList instanceof HTMLUListElement)
        || !(status instanceof HTMLElement)
        || !searchUrl
        || !emptyMessage
    ) {
        return;
    }

    const closeResults = () => {
        resultsList.classList.add('hidden');
        searchInput.setAttribute('aria-expanded', 'false');
        searchInput.removeAttribute('aria-activedescendant');
        activeOptionIndex = -1;
    };

    const updateActiveOption = (nextIndex) => {
        const options = Array.from(resultsList.querySelectorAll('[role="option"]'));

        if (options.length === 0) {
            return;
        }

        activeOptionIndex = (nextIndex + options.length) % options.length;
        options.forEach((option, index) => {
            const isActive = index === activeOptionIndex;
            option.classList.toggle('bg-emerald-50', isActive);
            option.classList.toggle('text-emerald-900', isActive);
            option.setAttribute('aria-selected', String(isActive));
        });

        const activeOption = options[activeOptionIndex];
        searchInput.setAttribute('aria-activedescendant', activeOption.id);
        activeOption.scrollIntoView({ block: 'nearest' });
    };

    const selectOption = (id, text) => {
        valueInput.value = id;
        searchInput.value = text;
        resultsList.replaceChildren();
        status.textContent = 'Sélection effectuée.';
        closeResults();
    };

    const displayOptions = (options) => {
        resultsList.replaceChildren();
        activeOptionIndex = -1;

        if (options.length === 0) {
            status.textContent = emptyMessage;
            resultsList.classList.add('hidden');
            searchInput.setAttribute('aria-expanded', 'false');
            return;
        }

        options.forEach((option, index) => {
            const item = document.createElement('li');
            const optionId = `${resultsList.id}-option-${index}`;

            item.id = optionId;
            item.tabIndex = -1;
            item.setAttribute('role', 'option');
            item.setAttribute('aria-selected', 'false');
            item.className = 'cursor-pointer px-4 py-2.5 text-sm text-slate-700 hover:bg-emerald-50';
            item.textContent = option.text;
            item.addEventListener('mousedown', (event) => event.preventDefault());
            item.addEventListener('click', () => selectOption(String(option.id), option.text));
            resultsList.append(item);
        });

        status.textContent = `${options.length} résultat${options.length > 1 ? 's' : ''}.`;
        resultsList.classList.remove('hidden');
        searchInput.setAttribute('aria-expanded', 'true');
    };

    const search = async (query) => {
        activeRequest?.abort();
        activeRequest = new AbortController();
        status.textContent = 'Recherche en cours…';

        try {
            const url = new URL(searchUrl, window.location.origin);
            url.searchParams.set('q', query);

            const response = await fetch(url, {
                headers: { Accept: 'application/json' },
                signal: activeRequest.signal,
            });

            if (!response.ok) {
                throw new Error(`La recherche a échoué (${response.status}).`);
            }

            const data = await response.json();

            if (!Array.isArray(data.results)) {
                throw new Error('La réponse de recherche est invalide.');
            }

            displayOptions(data.results);
        } catch (error) {
            if (error instanceof DOMException && error.name === 'AbortError') {
                return;
            }

            console.error(error);
            resultsList.replaceChildren();
            resultsList.classList.add('hidden');
            searchInput.setAttribute('aria-expanded', 'false');
            status.textContent = 'La recherche n’a pas pu aboutir. Réessayez.';
        }
    };

    searchInput.addEventListener('input', () => {
        valueInput.value = '';
        window.clearTimeout(searchTimeout);
        activeRequest?.abort();

        const query = searchInput.value.trim();
        if (query.length < 2) {
            resultsList.replaceChildren();
            closeResults();
            status.textContent = '';
            return;
        }

        searchTimeout = window.setTimeout(() => search(query), 250);
    });

    searchInput.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowDown') {
            event.preventDefault();
            resultsList.classList.remove('hidden');
            updateActiveOption(activeOptionIndex + 1);
        } else if (event.key === 'ArrowUp') {
            event.preventDefault();
            updateActiveOption(activeOptionIndex < 0 ? 0 : activeOptionIndex - 1);
        } else if (event.key === 'Enter' && activeOptionIndex >= 0) {
            event.preventDefault();
            const activeOption = resultsList.querySelectorAll('[role="option"]')[activeOptionIndex];
            activeOption?.click();
        } else if (event.key === 'Escape') {
            closeResults();
        }
    });

    document.addEventListener('click', (event) => {
        if (event.target instanceof Node && !searchSelect.contains(event.target)) {
            closeResults();
        }
    });
});

const sidebarDialog = document.querySelector('[data-sidebar-dialog]');

if (sidebarDialog instanceof HTMLDialogElement) {
    document.querySelector('[data-sidebar-open]')?.addEventListener('click', () => {
        sidebarDialog.showModal();
    });

    sidebarDialog.querySelector('[data-sidebar-close]')?.addEventListener('click', () => {
        sidebarDialog.close();
    });

    sidebarDialog.addEventListener('click', (event) => {
        if (event.target === sidebarDialog) {
            sidebarDialog.close();
        }
    });
}

const returnDialog = document.querySelector('[data-return-dialog]');
const returnForm = returnDialog?.querySelector('[data-return-form]');
const returnBookTitle = returnDialog?.querySelector('[data-return-book-title]');

if (
    returnDialog instanceof HTMLDialogElement
    && returnForm instanceof HTMLFormElement
    && returnBookTitle instanceof HTMLElement
) {
    document.addEventListener('click', (event) => {
        if (!(event.target instanceof Element)) {
            return;
        }

        const trigger = event.target.closest('[data-return-dialog-open]');

        if (!(trigger instanceof HTMLButtonElement)) {
            return;
        }

        returnForm.action = trigger.dataset.returnUrl;
        returnBookTitle.textContent = trigger.dataset.bookTitle;
        returnDialog.showModal();
    });

    returnDialog.querySelector('[data-return-dialog-close]')?.addEventListener('click', () => {
        returnDialog.close();
    });
}
