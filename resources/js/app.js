import './bootstrap';

/**
 * InnovateHub — app.js
 *
 * Demonstrates:
 *  - setTimeout        (alert auto-dismiss, typing indicator)
 *  - setInterval       (unread message badge polling)
 *  - Promise           (explicit Promise wrapper around fetch)
 *  - async/await       (all AJAX calls)
 *  - Event loop        (observable via the console logs and non-blocking UI)
 *  - AJAX (fetch API)  (search, currency, unread count)
 */

// ─────────────────────────────────────────────────────────────────────────────
// 1. AUTO-DISMISS ALERTS  (setTimeout)
// ─────────────────────────────────────────────────────────────────────────────
function initAlertAutoDismiss() {
    document.querySelectorAll('.alert.alert-success, .alert.alert-info').forEach(alert => {
        // setTimeout schedules this callback on the macrotask queue
        // — it won't block the rest of the page from rendering
        setTimeout(() => {
            alert.classList.add('fade');
            setTimeout(() => alert.remove(), 400); // nested: wait for CSS fade
        }, 4000);
    });
}

// ─────────────────────────────────────────────────────────────────────────────
// 2. UNREAD MESSAGE BADGE POLLING  (setInterval + async/await + Promise)
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Wraps fetch in an explicit Promise to demonstrate the Promise constructor.
 * Resolves with parsed JSON, rejects on HTTP errors.
 */
function fetchJson(url) {
    return new Promise((resolve, reject) => {
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
        })
        .then(response => {
            if (! response.ok) {
                reject(new Error(`HTTP ${response.status}`));
                return;
            }
            return response.json();
        })
        .then(data => resolve(data))
        .catch(err => reject(err));
    });
}

async function refreshUnreadBadge() {
    try {
        // async/await pauses HERE but does NOT block the browser's event loop
        const data = await fetchJson('/api/messages/unread-count');

        const badges = document.querySelectorAll('.unread-badge');

        badges.forEach(badge => {
            if (data.count > 0) {
                badge.textContent = data.count;
                badge.classList.remove('d-none');
            } else {
                badge.classList.add('d-none');
            }
        });
    } catch (err) {
        // Silently ignore — network errors shouldn't crash the page
        console.warn('Badge refresh failed:', err.message);
    }
}

function initUnreadBadgePolling() {
    const badge = document.querySelector('.unread-badge');
    if (! badge) return;

    // Poll every 30 seconds using setInterval (macrotask queue)
    refreshUnreadBadge(); // immediate first call
    setInterval(refreshUnreadBadge, 30_000);
}

// ─────────────────────────────────────────────────────────────────────────────
// 3. LIVE STARTUP SEARCH  (async/await + AJAX + DOM manipulation)
// ─────────────────────────────────────────────────────────────────────────────
function initLiveSearch() {
    const searchInput    = document.getElementById('liveSearchInput');
    const categorySelect = document.getElementById('liveCategorySelect');
    const resultsGrid    = document.getElementById('searchResultsGrid');
    const resultCount    = document.getElementById('resultCount');
    const loader         = document.getElementById('searchLoader');

    if (! searchInput || ! resultsGrid) return;

    let debounceTimer = null; // holds our setTimeout reference

    async function performSearch() {
        const q        = searchInput.value.trim();
        const category = categorySelect ? categorySelect.value : '';

        const params = new URLSearchParams();
        if (q)        params.set('q', q);
        if (category) params.set('category', category);

        loader?.classList.remove('d-none');

        try {
            // async/await — the event loop is free to handle other tasks
            // while the network request is in flight
            const data = await fetchJson(`/api/startups/search?${params}`);

            if (resultCount) {
                resultCount.textContent = `${data.count} startup${data.count !== 1 ? 's' : ''} found`;
            }

            resultsGrid.innerHTML = data.data.length
                ? data.data.map(renderStartupCard).join('')
                : `<div class="col-12">
                       <div class="alert alert-info">No startups match your search.</div>
                   </div>`;
        } catch (err) {
            resultsGrid.innerHTML = `<div class="col-12">
                <div class="alert alert-danger">Search failed. Please try again.</div>
            </div>`;
        } finally {
            loader?.classList.add('d-none');
        }
    }

    function renderStartupCard(startup) {
        const thumbnail = startup.thumbnail
            ? `<img src="${startup.thumbnail}" class="card-img-top"
                    style="height:160px; object-fit:cover" alt="${startup.title}">`
            : `<div class="bg-light d-flex align-items-center justify-content-center"
                    style="height:160px">
                   <span class="text-muted">No image</span>
               </div>`;

        const interestBadge = startup.has_interest
            ? `<span class="badge bg-success">Interested</span>`
            : '';

        return `
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    ${thumbnail}
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h6 class="mb-0">${startup.title}</h6>
                            <span class="badge bg-secondary">${startup.category}</span>
                        </div>
                        ${startup.tagline ? `<p class="text-primary small mb-2">${startup.tagline}</p>` : ''}
                        <p class="text-muted small mb-1">By ${startup.founder_name}</p>
                        <p class="small">${startup.description}</p>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <small class="text-muted">${startup.interest_count} interested</small>
                        <div class="d-flex gap-1 align-items-center">
                            ${interestBadge}
                            <a href="${startup.url}" class="btn btn-sm btn-outline-primary">View</a>
                        </div>
                    </div>
                </div>
            </div>`;
    }

    // Debounce: cancel any pending search, schedule a new one
    // This is a practical demonstration of setTimeout + event loop
    function debounceSearch() {
        clearTimeout(debounceTimer);
        // Schedule on macrotask queue — fires after 400ms idle
        debounceTimer = setTimeout(performSearch, 400);
    }

    searchInput.addEventListener('input', debounceSearch);
    categorySelect?.addEventListener('change', performSearch);

    // Run on page load to populate results immediately
    performSearch();
}

// ─────────────────────────────────────────────────────────────────────────────
// 4. CURRENCY CONVERTER  (async/await + Promise chaining)
// ─────────────────────────────────────────────────────────────────────────────
function initCurrencyConverter() {
    const widget = document.getElementById('currencyWidget');
    if (! widget) return;

    const amountInput   = widget.querySelector('#convertAmount');
    const currencySelect = widget.querySelector('#convertTo');
    const resultDisplay = widget.querySelector('#convertResult');
    const rateDisplay   = widget.querySelector('#currentRate');

    async function loadRates() {
        try {
            // Promise chain: fetchJson returns a Promise, .then() processes it
            const data = await fetchJson('/api/currency/rates');
            const rates = data.data.rates;

            rateDisplay.innerHTML = Object.entries(rates)
                .map(([code, rate]) =>
                    `<span class="badge bg-light text-dark me-1">
                        1 USD = ${rate} ${code}
                    </span>`)
                .join('');
        } catch {
            rateDisplay.innerHTML = '<small class="text-muted">Rates unavailable</small>';
        }
    }

    async function convertCurrency() {
        const amount = parseFloat(amountInput?.value);
        const to     = currencySelect?.value;

        if (! amount || ! to) return;

        resultDisplay.innerHTML = '<span class="text-muted small">Converting...</span>';

        try {
            // Demonstrates how async/await lets us write async logic linearly
            const data = await fetchJson(`/api/currency/convert?amount=${amount}&to=${to}`);

            resultDisplay.innerHTML = `
                <div class="fw-bold text-success fs-5">
                    ${data.result.toLocaleString()} ${data.currency}
                </div>
                <small class="text-muted">= $${amount} USD</small>`;
        } catch {
            resultDisplay.innerHTML = '<small class="text-danger">Conversion failed.</small>';
        }
    }

    amountInput?.addEventListener('input', () => {
        // Debounce conversion calls too
        clearTimeout(amountInput._timer);
        amountInput._timer = setTimeout(convertCurrency, 500);
    });

    currencySelect?.addEventListener('change', convertCurrency);

    // Load rates on mount — demonstrates Promise-based init
    loadRates().then(() => {
        console.log('Exchange rates loaded'); // event loop: microtask resolved
    });
}

// ─────────────────────────────────────────────────────────────────────────────
// 5. TYPING INDICATOR IN MESSAGING  (setTimeout + DOM)
// ─────────────────────────────────────────────────────────────────────────────
function initTypingIndicator() {
    const messageInput = document.getElementById('messageBodyInput');
    const typingDot    = document.getElementById('typingIndicator');

    if (! messageInput || ! typingDot) return;

    let typingTimer = null;

    messageInput.addEventListener('input', () => {
        typingDot.classList.remove('d-none');

        // Clear the previously scheduled hide
        clearTimeout(typingTimer);

        // Schedule hide after 1.5s of inactivity (setTimeout on macrotask queue)
        typingTimer = setTimeout(() => {
            typingDot.classList.add('d-none');
        }, 1500);
    });
}

// ─────────────────────────────────────────────────────────────────────────────
// 6. AJAX MESSAGE SEND  (async/await + fetch, no full page reload)
// ─────────────────────────────────────────────────────────────────────────────
function initAjaxMessageSend() {
    const form    = document.getElementById('messageForm');
    const thread  = document.getElementById('thread');
    const input   = document.getElementById('messageBodyInput');

    if (! form || ! thread) return;

    form.addEventListener('submit', async (e) => {
        e.preventDefault(); // prevent full page reload

        const body   = input?.value.trim();
        if (! body) return;

        const url    = form.getAttribute('action');
        const token  = form.querySelector('[name="_token"]')?.value;

        // Optimistic UI — add the bubble immediately before the request returns
        const tempId = `temp-${Date.now()}`;
        thread.insertAdjacentHTML('beforeend', `
            <div id="${tempId}" class="d-flex justify-content-end mb-3">
                <div class="chat-bubble mine opacity-50">${escapeHtml(body)}</div>
            </div>`);

        thread.scrollTop = thread.scrollHeight;
        input.value = '';

        try {
            // AJAX POST — async/await keeps code readable
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({ body }),
            });

            if (! response.ok) throw new Error(`HTTP ${response.status}`);

            // Replace optimistic bubble with confirmed one
            const tempBubble = document.getElementById(tempId);
            if (tempBubble) {
                tempBubble.querySelector('.chat-bubble').classList.remove('opacity-50');
                tempBubble.removeAttribute('id');
            }
        } catch (err) {
            // Mark the bubble red on failure
            const tempBubble = document.getElementById(tempId);
            if (tempBubble) {
                tempBubble.querySelector('.chat-bubble').classList.add('bg-danger');
                tempBubble.querySelector('.chat-bubble').classList.remove('mine');
                tempBubble.insertAdjacentHTML('beforeend',
                    `<small class="text-danger d-block text-end">Failed to send</small>`);
            }
        }
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.appendChild(document.createTextNode(text));
    return div.innerHTML;
}

// ─────────────────────────────────────────────────────────────────────────────
// BOOT — runs after DOM is ready
// ─────────────────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    /**
     * Event Loop note:
     * DOMContentLoaded fires as a macrotask once the HTML is parsed.
     * Each init function below runs synchronously in this callback.
     * Any setTimeout/setInterval they register are queued as future macrotasks.
     * Any Promise resolutions (fetchJson, async functions) are queued
     * as microtasks — which run before the next macrotask.
     */
    initAlertAutoDismiss();
    initUnreadBadgePolling();
    initLiveSearch();
    initCurrencyConverter();
    initTypingIndicator();
    initAjaxMessageSend();
});