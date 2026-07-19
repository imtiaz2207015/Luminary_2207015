/**
 * reflection-quote.js
 *
 * Handles the "Get inspired" button on the capsule/post creation forms.
 * Fetches a random quote from our Laravel /quote-json endpoint using
 * fetch + async/await, then fills in the hidden quote_text / quote_author
 * inputs so the quote is submitted along with the capsule or post form
 * (without a full page reload).
 */

document.addEventListener('DOMContentLoaded', function () {
    const inspireButtons = document.querySelectorAll('.js-get-inspired-btn');

    inspireButtons.forEach(function (button) {
        button.addEventListener('click', async function (event) {
            event.preventDefault();

            const targetId = button.dataset.target;
            const resultBox = document.getElementById(targetId + '-result');
            const quoteTextInput = document.getElementById(targetId + '-quote-text');
            const quoteAuthorInput = document.getElementById(targetId + '-quote-author');
            const url = button.dataset.url;

            const originalLabel = button.textContent;
            button.disabled = true;
            button.textContent = 'Loading...';
            resultBox.innerHTML = '';

            try {
                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/json',
                    },
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.error || 'Could not fetch a quote.');
                }

                quoteTextInput.value = data.quote_text;
                quoteAuthorInput.value = data.quote_author;

                resultBox.innerHTML = `
    <blockquote class="reflection-quote" style="border-left: 3px solid var(--gold, #c9a84c); padding: 12px 16px; margin: 10px 0; background: rgba(255,255,255,0.03); border-radius: 8px; color: #f5f0e8; font-style: italic;">
        "${data.quote_text}"
        <footer style="margin-top: 6px; font-style: normal; font-size: 0.82rem; color: #8b95a8;">&mdash; ${data.quote_author}</footer>
    </blockquote>
    <button type="button" class="btn btn-danger-soft js-remove-quote-btn" data-target="${targetId}">
        <i class="bi bi-x-circle"></i> Remove quote
    </button>
`;
            } catch (error) {
                resultBox.innerHTML = `<p class="reflection-quote-error">${error.message}</p>`;
            } finally {
                button.disabled = false;
                button.textContent = originalLabel;
            }
        });
    });

    document.addEventListener('click', function (event) {
        if (!event.target.classList.contains('js-remove-quote-btn')) {
            return;
        }

        const targetId = event.target.dataset.target;
        document.getElementById(targetId + '-quote-text').value = '';
        document.getElementById(targetId + '-quote-author').value = '';
        document.getElementById(targetId + '-result').innerHTML = '';
    });
});