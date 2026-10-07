import './bootstrap';

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
