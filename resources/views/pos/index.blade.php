@extends('layouts.app')

@section('title', 'POS Billing')

@section('content')
    <div class="space-y-6">
        <div class="rounded-[28px] border border-white/10 bg-[linear-gradient(135deg,rgba(212,175,55,0.14),rgba(255,255,255,0.02))] p-5 shadow-glow backdrop-blur-xl sm:p-7">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs uppercase tracking-[0.22em] text-gold/80">Billing Studio</p>
                    <h1 class="mt-3 text-3xl font-semibold tracking-[0.08em] text-ivory sm:text-4xl">POS Billing</h1>
                    <p class="mt-3 max-w-2xl text-sm leading-7 text-white/60 sm:text-base">Designed for quick taps in a real shop. Select products, adjust quantities, and complete checkout with minimal friction.</p>
                </div>
                <div class="grid grid-cols-2 gap-3 sm:w-auto">
                    <div class="rounded-2xl border border-white/10 bg-black/25 px-4 py-3">
                        <p class="text-xs uppercase tracking-[0.16em] text-white/40">Products</p>
                        <p class="mt-2 text-2xl font-semibold text-ivory">{{ $products->count() }}</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-black/25 px-4 py-3">
                        <p class="text-xs uppercase tracking-[0.16em] text-white/40">Ready</p>
                        <p class="mt-2 text-2xl font-semibold text-gold">Checkout</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid gap-5 lg:grid-cols-[1.6fr_0.9fr]">
            <section>
                @if ($products->isEmpty())
                    <div class="rounded-[28px] border border-dashed border-white/15 bg-white/5 px-4 py-12 text-center backdrop-blur-xl">
                        <p class="text-base font-medium text-ivory">No products available.</p>
                    </div>
                @else
                    <div class="mb-4 rounded-[24px] border border-white/10 bg-white/5 p-4 shadow-glow backdrop-blur-xl">
                        <label for="product-search" class="mb-2 block text-xs uppercase tracking-[0.18em] text-gold/75">Search products</label>
                        <input id="product-search" type="text" placeholder="Search by product name" class="w-full rounded-xl border border-white/10 bg-black/20 px-4 py-4 text-sm text-ivory outline-none placeholder:text-white/25">
                    </div>

                    <div id="product-search-empty" class="hidden rounded-[24px] border border-dashed border-white/15 bg-white/5 px-4 py-10 text-center text-sm text-white/45 backdrop-blur-xl">
                        No products match your search.
                    </div>

                    <div id="product-grid" class="grid grid-cols-2 gap-3 sm:grid-cols-3 xl:grid-cols-4">
                        @foreach ($products as $product)
                            <button
                                type="button"
                                class="product-card rounded-[24px] border p-4 text-left shadow-glow backdrop-blur-xl transition duration-200 sm:p-5 {{ $product->stock > 0 ? 'border-white/10 bg-white/5 hover:-translate-y-0.5 hover:border-gold/40 hover:bg-white/[0.07]' : 'border-rose-500/20 bg-rose-500/10 opacity-80' }}"
                                data-product-id="{{ $product->id }}"
                                data-product-name="{{ $product->name }}"
                                data-product-search="{{ \Illuminate\Support\Str::lower($product->name) }}"
                                data-product-price="{{ $product->price }}"
                                data-product-stock="{{ $product->stock }}"
                            >
                                <div class="flex h-full flex-col justify-between gap-5">
                                    <div class="space-y-3">
                                        <span data-stock-badge class="inline-flex rounded-full px-2.5 py-1 text-[11px] uppercase tracking-[0.16em] {{ $product->stock > 0 ? 'border border-gold/20 bg-gold/10 text-gold/85' : 'border border-rose-400/30 bg-rose-500/10 text-rose-200' }}">
                                            {{ $product->stock > 0 ? 'In stock' : 'Out of stock' }}
                                        </span>
                                        <h2 class="line-clamp-2 text-base font-semibold tracking-[0.03em] text-ivory">{{ $product->name }}</h2>
                                    </div>
                                    <div class="space-y-2">
                                        <p class="text-2xl font-semibold text-gold">{{ number_format((float) $product->price, 2) }}</p>
                                        <div class="flex items-center justify-between text-xs uppercase tracking-[0.14em] text-white/40">
                                            <span>Available</span>
                                            <span data-stock-label>{{ $product->stock }}</span>
                                        </div>
                                    </div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                @endif
            </section>

            <aside class="rounded-[28px] border border-white/10 bg-white/5 p-4 shadow-glow backdrop-blur-xl sm:p-5">
                <div class="mb-5 flex items-center justify-between">
                    <div>
                        <p class="text-xs uppercase tracking-[0.18em] text-gold/75">Current order</p>
                        <h2 class="mt-2 text-xl font-semibold tracking-[0.05em] text-ivory">Cart</h2>
                    </div>
                    <button id="clear-cart" type="button" class="rounded-xl border border-white/10 bg-black/20 px-3 py-2 text-sm text-white/55 transition hover:border-gold/30 hover:text-gold">Clear</button>
                </div>

                <div id="cart-empty" class="rounded-2xl border border-dashed border-white/10 bg-black/20 px-4 py-8 text-center text-sm text-white/45">No items added yet.</div>
                <div id="cart-items" class="hidden space-y-3"></div>

                <div class="mt-5 border-t border-white/10 pt-5">
                    <div class="flex items-center justify-between text-sm text-white/45">
                        <span class="uppercase tracking-[0.14em]">Total</span>
                        <span id="cart-total" class="text-3xl font-semibold text-gold">0.00</span>
                    </div>

                    <div class="mt-5">
                        <p class="text-xs uppercase tracking-[0.16em] text-white/45">Payment method</p>
                        <div class="mt-3 grid grid-cols-2 gap-3">
                            <label class="payment-option rounded-2xl border border-gold/30 bg-gold/10 px-4 py-3 text-sm font-medium text-gold"><input type="radio" name="payment_method" value="cash" class="sr-only" checked>Cash</label>
                            <label class="payment-option rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-sm font-medium text-white/70"><input type="radio" name="payment_method" value="card" class="sr-only">Card</label>
                        </div>
                    </div>

                    <div class="mt-5 rounded-2xl border border-white/10 bg-black/20 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-xs uppercase tracking-[0.16em] text-white/45">Customer</p>
                                <p class="mt-1 text-sm text-white/60">Saved customers auto-fill by name or phone.</p>
                            </div>
                            <label class="flex items-center gap-2 text-sm text-white/75"><input id="walking-customer" type="checkbox" class="h-4 w-4 rounded border-white/20 bg-black/30 text-gold focus:ring-gold/40">Walking Customer</label>
                        </div>

                        <div class="mt-4 space-y-4">
                            <div>
                                <label for="customer-name" class="mb-2 block text-xs uppercase tracking-[0.16em] text-white/45">Customer name</label>
                                <input id="customer-name" type="text" placeholder="Enter customer name" class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-3 text-sm text-ivory outline-none placeholder:text-white/25">
                            </div>
                            <div>
                                <label for="customer-phone" class="mb-2 block text-xs uppercase tracking-[0.16em] text-white/45">Phone number</label>
                                <input id="customer-phone" type="text" placeholder="9477XXXXXXX" class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-3 text-sm text-ivory outline-none placeholder:text-white/25">
                            </div>
                            <div id="customer-suggestions" class="hidden rounded-xl border border-white/10 bg-white/5 p-2"></div>
                            <div id="matched-customer" class="hidden rounded-xl border border-emerald-400/20 bg-emerald-500/10 px-4 py-3">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.16em] text-emerald-200/80">Matched customer</p>
                                        <p id="matched-customer-name" class="mt-1 text-sm font-semibold text-ivory"></p>
                                        <p id="matched-customer-phone" class="mt-1 text-xs text-white/55"></p>
                                    </div>
                                    <span id="matched-customer-points" class="rounded-full border border-gold/20 bg-gold/10 px-3 py-1 text-xs font-medium text-gold"></span>
                                </div>
                            </div>
                            <div class="rounded-xl border border-white/10 bg-white/5 px-4 py-3"><div class="flex items-center justify-between text-sm text-white/55"><span>Available points</span><span id="available-points" class="text-lg font-semibold text-gold">0</span></div></div>
                            <div>
                                <label for="redeemed-points" class="mb-2 block text-xs uppercase tracking-[0.16em] text-white/45">Redeem points</label>
                                <input id="redeemed-points" type="number" min="0" step="1" value="0" class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-3 text-sm text-ivory outline-none placeholder:text-white/25">
                            </div>
                            <div class="rounded-xl border border-white/10 bg-white/5 px-4 py-3"><div class="flex items-center justify-between text-sm text-white/55"><span>Points discount</span><span id="points-discount" class="text-lg font-semibold text-gold">0.00</span></div></div>
                            <div>
                                <label for="amount-paid" class="mb-2 block text-xs uppercase tracking-[0.16em] text-white/45">Amount paid</label>
                                <input id="amount-paid" type="number" min="0" step="0.01" placeholder="0.00" class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-3 text-sm text-ivory outline-none placeholder:text-white/25">
                            </div>
                            <div class="rounded-xl border border-white/10 bg-white/5 px-4 py-3"><div class="flex items-center justify-between text-sm text-white/55"><span>Final total</span><span id="final-total" class="text-lg font-semibold text-gold">0.00</span></div></div>
                            <div class="rounded-xl border border-white/10 bg-white/5 px-4 py-3"><div class="flex items-center justify-between text-sm text-white/55"><span>Balance to give</span><span id="balance-amount" class="text-lg font-semibold text-gold">0.00</span></div></div>
                        </div>
                    </div>

                    <button id="print-receipt-button" type="button" class="mt-5 w-full rounded-xl bg-gold px-4 py-4 text-sm font-semibold uppercase tracking-[0.18em] text-black transition hover:bg-[#e6c766] disabled:cursor-not-allowed disabled:bg-white/20 disabled:text-white/35" disabled>Print Receipt</button>
                    <button id="send-whatsapp-button" type="button" class="mt-3 w-full rounded-xl border border-white/10 bg-white/5 px-4 py-4 text-sm font-semibold uppercase tracking-[0.18em] text-white/80 transition hover:border-gold/40 hover:text-gold disabled:cursor-not-allowed disabled:border-white/10 disabled:bg-white/5 disabled:text-white/30" disabled>Send WhatsApp</button>
                    <p id="checkout-message" class="mt-3 text-sm"></p>
                </div>
            </aside>
        </div>
    </div>

    <script>
        (() => {
            const products = new Map();
            const cart = new Map();
            document.querySelectorAll('.product-card').forEach((card) => {
                products.set(Number(card.dataset.productId), { id: Number(card.dataset.productId), name: card.dataset.productName, price: Number(card.dataset.productPrice), stock: Number(card.dataset.productStock), card });
                card.addEventListener('click', () => addToCart(Number(card.dataset.productId)));
            });

            const cartItems = document.getElementById('cart-items');
            const cartEmpty = document.getElementById('cart-empty');
            const cartTotal = document.getElementById('cart-total');
            const printReceiptButton = document.getElementById('print-receipt-button');
            const sendWhatsAppButton = document.getElementById('send-whatsapp-button');
            const clearCartButton = document.getElementById('clear-cart');
            const checkoutMessage = document.getElementById('checkout-message');
            const paymentOptions = Array.from(document.querySelectorAll('.payment-option'));
            const productSearch = document.getElementById('product-search');
            const productSearchEmpty = document.getElementById('product-search-empty');
            const customerNameInput = document.getElementById('customer-name');
            const customerPhoneInput = document.getElementById('customer-phone');
            const amountPaidInput = document.getElementById('amount-paid');
            const balanceAmount = document.getElementById('balance-amount');
            const walkingCustomerInput = document.getElementById('walking-customer');
            const redeemedPointsInput = document.getElementById('redeemed-points');
            const availablePoints = document.getElementById('available-points');
            const pointsDiscount = document.getElementById('points-discount');
            const finalTotal = document.getElementById('final-total');
            const matchedCustomer = document.getElementById('matched-customer');
            const matchedCustomerName = document.getElementById('matched-customer-name');
            const matchedCustomerPhone = document.getElementById('matched-customer-phone');
            const matchedCustomerPoints = document.getElementById('matched-customer-points');
            const customerSuggestions = document.getElementById('customer-suggestions');
            let customerLookupTimeout = null;
            let currentCustomer = null;

            const getPaymentMethod = () => document.querySelector('input[name="payment_method"]:checked')?.value ?? 'cash';
            const getCartTotalAmount = () => Array.from(cart.values()).reduce((total, item) => total + (item.price * item.quantity), 0);
            const getRedeemedPoints = () => Math.max(0, Math.floor(Math.min(Number(redeemedPointsInput.value || 0), currentCustomer?.points_balance ?? 0, getCartTotalAmount())));
            const getFinalTotalAmount = () => Math.max(0, getCartTotalAmount() - getRedeemedPoints());

            function updateBalance() {
                const total = getFinalTotalAmount();
                const amountPaid = Number(amountPaidInput.value || 0);
                balanceAmount.textContent = Math.max(0, amountPaid - total).toFixed(2);
                finalTotal.textContent = total.toFixed(2);
                pointsDiscount.textContent = getRedeemedPoints().toFixed(2);
            }

            function renderMatchedCustomer() {
                if (!currentCustomer || walkingCustomerInput.checked) {
                    matchedCustomer.classList.add('hidden');
                    matchedCustomerName.textContent = '';
                    matchedCustomerPhone.textContent = '';
                    matchedCustomerPoints.textContent = '';
                    return;
                }
                matchedCustomer.classList.remove('hidden');
                matchedCustomerName.textContent = currentCustomer.name;
                matchedCustomerPhone.textContent = currentCustomer.phone || 'No phone number';
                matchedCustomerPoints.textContent = `${currentCustomer.points_balance} pts`;
            }

            function renderCustomerSuggestions(customers) {
                customerSuggestions.innerHTML = '';

                if (walkingCustomerInput.checked || customers.length === 0) {
                    customerSuggestions.classList.add('hidden');
                    return;
                }

                customers.forEach((customer) => {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.className = 'flex w-full items-center justify-between rounded-lg px-3 py-2 text-left transition hover:bg-black/20';
                    button.innerHTML = `
                        <div>
                            <p class="text-sm font-medium text-ivory">${escapeHtml(customer.name)}</p>
                            <p class="text-xs text-white/45">${escapeHtml(customer.phone || 'No phone number')}</p>
                        </div>
                        <span class="text-xs font-medium text-gold">${customer.points_balance} pts</span>
                    `;
                    button.addEventListener('click', () => {
                        applyMatchedCustomer(customer);
                        customerSuggestions.classList.add('hidden');
                    });
                    customerSuggestions.appendChild(button);
                });

                customerSuggestions.classList.remove('hidden');
            }

            function applyMatchedCustomer(customer) {
                currentCustomer = customer;
                availablePoints.textContent = String(customer?.points_balance ?? 0);
                if (!customer) {
                    redeemedPointsInput.value = '0';
                    customerSuggestions.classList.add('hidden');
                    renderMatchedCustomer();
                    updateBalance();
                    syncActionButtons();
                    return;
                }
                customerNameInput.value = customer.name || '';
                customerPhoneInput.value = customer.phone || '';
                customerSuggestions.classList.add('hidden');
                if ((customer.points_balance ?? 0) === 0) {
                    redeemedPointsInput.value = '0';
                } else if (Number(redeemedPointsInput.value || 0) > customer.points_balance) {
                    redeemedPointsInput.value = String(customer.points_balance);
                }
                renderMatchedCustomer();
                updateBalance();
                syncActionButtons();
            }

            function syncCustomerFields() {
                const walking = walkingCustomerInput.checked;
                customerNameInput.disabled = walking;
                customerPhoneInput.disabled = walking;
                redeemedPointsInput.disabled = walking;
                if (walking) {
                    customerNameInput.value = 'Walking Customer';
                    customerPhoneInput.value = '';
                    applyMatchedCustomer(null);
                } else if (customerNameInput.value === 'Walking Customer') {
                    customerNameInput.value = '';
                }
                renderMatchedCustomer();
                syncActionButtons();
                updateBalance();
            }

            function syncActionButtons() {
                const hasItems = cart.size > 0;
                const total = getFinalTotalAmount();
                const amountPaid = Number(amountPaidInput.value || 0);
                const walking = walkingCustomerInput.checked;
                const phone = customerPhoneInput.value.trim();
                const canCheckout = hasItems && amountPaid >= total;
                printReceiptButton.disabled = !canCheckout;
                sendWhatsAppButton.disabled = !canCheckout || walking || phone === '';
            }

            function syncPaymentStyles() {
                paymentOptions.forEach((option) => {
                    const input = option.querySelector('input');
                    option.className = input.checked
                        ? 'payment-option rounded-2xl border border-gold/30 bg-gold/10 px-4 py-3 text-sm font-medium text-gold'
                        : 'payment-option rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-sm font-medium text-white/70';
                });
            }

            paymentOptions.forEach((option) => option.addEventListener('click', () => {
                option.querySelector('input').checked = true;
                syncPaymentStyles();
            }));

            function syncProductCardState(product) {
                const inStock = product.stock > 0;
                const badge = product.card.querySelector('[data-stock-badge]');
                product.card.className = inStock
                    ? 'product-card rounded-[24px] border border-white/10 bg-white/5 p-4 text-left shadow-glow backdrop-blur-xl transition duration-200 hover:-translate-y-0.5 hover:border-gold/40 hover:bg-white/[0.07] sm:p-5'
                    : 'product-card rounded-[24px] border border-rose-500/20 bg-rose-500/10 p-4 text-left shadow-glow backdrop-blur-xl opacity-80 transition duration-200 sm:p-5';
                badge.className = inStock
                    ? 'inline-flex rounded-full border border-gold/20 bg-gold/10 px-2.5 py-1 text-[11px] uppercase tracking-[0.16em] text-gold/85'
                    : 'inline-flex rounded-full border border-rose-400/30 bg-rose-500/10 px-2.5 py-1 text-[11px] uppercase tracking-[0.16em] text-rose-200';
                badge.textContent = inStock ? 'In stock' : 'Out of stock';
                product.card.querySelector('[data-stock-label]').textContent = String(product.stock);
            }

            function filterProducts() {
                const term = (productSearch.value || '').trim().toLowerCase();
                let visibleCount = 0;
                products.forEach((product) => {
                    const matches = term === '' || product.card.dataset.productSearch.includes(term);
                    product.card.classList.toggle('hidden', !matches);
                    if (matches) visibleCount += 1;
                });
                productSearchEmpty.classList.toggle('hidden', visibleCount !== 0);
            }

            async function lookupCustomer() {
                if (walkingCustomerInput.checked) return;
                const query = customerPhoneInput.value.trim() || customerNameInput.value.trim();
                if (query === '') {
                    applyMatchedCustomer(null);
                    return;
                }
                const response = await fetch(`${@json(route('pos.customers.lookup'))}?query=${encodeURIComponent(query)}`, { headers: { Accept: 'application/json' } });
                const data = await response.json();
                renderCustomerSuggestions(data.customers || []);
            }

            function queueCustomerLookup() {
                clearTimeout(customerLookupTimeout);
                customerLookupTimeout = setTimeout(() => lookupCustomer().catch(() => applyMatchedCustomer(null)), 250);
            }

            function addToCart(productId) {
                const product = products.get(productId);
                if (!product || product.stock < 1) return;
                const nextQuantity = (cart.get(productId)?.quantity ?? 0) + 1;
                if (nextQuantity > product.stock) {
                    setMessage(`Only ${product.stock} in stock for ${product.name}.`, 'text-red-300');
                    return;
                }
                cart.set(productId, { ...product, quantity: nextQuantity });
                setMessage('', '');
                renderCart();
            }

            function updateQuantity(productId, delta) {
                const item = cart.get(productId);
                if (!item) return;
                const nextQuantity = item.quantity + delta;
                if (nextQuantity <= 0) {
                    cart.delete(productId);
                } else if (nextQuantity <= item.stock) {
                    cart.set(productId, { ...item, quantity: nextQuantity });
                } else {
                    setMessage(`Only ${item.stock} in stock for ${item.name}.`, 'text-red-300');
                    return;
                }
                setMessage('', '');
                renderCart();
            }

            function renderCart() {
                const items = Array.from(cart.values());
                cartItems.innerHTML = '';
                if (items.length === 0) {
                    cartItems.classList.add('hidden');
                    cartEmpty.classList.remove('hidden');
                    cartTotal.textContent = '0.00';
                    syncActionButtons();
                    updateBalance();
                    return;
                }
                cartItems.classList.remove('hidden');
                cartEmpty.classList.add('hidden');
                let total = 0;
                items.forEach((item) => {
                    total += item.price * item.quantity;
                    const row = document.createElement('div');
                    row.className = 'rounded-2xl border border-white/10 bg-black/20 p-3.5';
                    row.innerHTML = `<div class="flex items-start justify-between gap-3"><div><p class="text-sm font-semibold tracking-[0.03em] text-ivory">${escapeHtml(item.name)}</p><p class="text-xs uppercase tracking-[0.14em] text-white/40">${item.price.toFixed(2)} each</p></div><p class="text-sm font-semibold text-gold">${(item.price * item.quantity).toFixed(2)}</p></div><div class="mt-3 flex items-center gap-2"><button type="button" class="qty-btn rounded-xl border border-white/10 bg-white/5 px-3.5 py-2.5 text-sm text-white/80" data-action="decrease" data-product-id="${item.id}">-</button><span class="min-w-8 text-center text-sm font-medium text-ivory">${item.quantity}</span><button type="button" class="qty-btn rounded-xl border border-white/10 bg-white/5 px-3.5 py-2.5 text-sm text-white/80" data-action="increase" data-product-id="${item.id}">+</button></div>`;
                    cartItems.appendChild(row);
                });
                cartItems.querySelectorAll('.qty-btn').forEach((button) => button.addEventListener('click', () => updateQuantity(Number(button.dataset.productId), button.dataset.action === 'increase' ? 1 : -1)));
                cartTotal.textContent = total.toFixed(2);
                syncActionButtons();
                updateBalance();
            }

            function normalizePhone(phone) {
                return phone.replace(/[^\d]/g, '');
            }

            function buildWhatsAppUrl(data) {
                const phone = normalizePhone(data.sale.customer_phone || '');
                if (!phone) return null;
                const text = [`${@json($business->name)} Receipt`, `Receipt #${data.sale.id}`, `Customer: ${data.sale.customer_name}`, `Payment: ${String(data.sale.payment_method).toUpperCase()}`, `Total: ${Number(data.sale.total_amount).toFixed(2)}`, `Paid: ${Number(data.sale.amount_paid).toFixed(2)}`, `Balance: ${Number(data.sale.balance_amount).toFixed(2)}`].join('\n');
                return `https://wa.me/${phone}?text=${encodeURIComponent(text)}`;
            }

            async function checkout(action) {
                const items = Array.from(cart.values()).map((item) => ({ product_id: item.id, quantity: item.quantity }));
                if (items.length === 0) return;
                printReceiptButton.disabled = true;
                sendWhatsAppButton.disabled = true;
                const activeButton = action === 'whatsapp' ? sendWhatsAppButton : printReceiptButton;
                activeButton.textContent = 'Processing...';
                setMessage('', '');
                let receiptWindow = null;
                if (action === 'print') receiptWindow = window.open('', '_blank', 'noopener');

                try {
                    const response = await fetch(@json(route('pos.checkout')), {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': @json(csrf_token()) },
                        body: JSON.stringify({ items, payment_method: getPaymentMethod(), customer_name: customerNameInput.value, customer_phone: customerPhoneInput.value, amount_paid: amountPaidInput.value, is_walking_customer: walkingCustomerInput.checked, redeemed_points: getRedeemedPoints() }),
                    });
                    const data = await response.json();
                    if (!response.ok) {
                        const firstError = data.errors ? Object.values(data.errors)[0]?.[0] : null;
                        throw new Error(firstError || data.message || 'Checkout failed.');
                    }
                    items.forEach((item) => {
                        const product = products.get(item.product_id);
                        if (!product) return;
                        product.stock -= item.quantity;
                        product.card.dataset.productStock = String(product.stock);
                        syncProductCardState(product);
                    });
                    cart.clear();
                    renderCart();
                    setMessage(`Sale #${data.sale.id} completed by ${data.sale.payment_method}. Total ${Number(data.sale.total_amount).toFixed(2)}.`, 'text-gold');
                    if (action === 'print' && data.receipt_url) {
                        if (receiptWindow) receiptWindow.location = data.receipt_url;
                        else window.open(data.receipt_url, '_blank', 'noopener');
                    }
                    if (action === 'whatsapp') {
                        const whatsappUrl = buildWhatsAppUrl(data);
                        if (!whatsappUrl) throw new Error('Customer phone number is required to send WhatsApp.');
                        window.open(whatsappUrl, '_blank', 'noopener');
                    }
                    amountPaidInput.value = '';
                    redeemedPointsInput.value = '0';
                    applyMatchedCustomer(data.customer ?? null);
                } catch (error) {
                    if (receiptWindow) receiptWindow.close();
                    setMessage(error.message, 'text-red-300');
                } finally {
                    printReceiptButton.textContent = 'Print Receipt';
                    sendWhatsAppButton.textContent = 'Send WhatsApp';
                    syncActionButtons();
                }
            }

            function setMessage(message, className) {
                checkoutMessage.className = `mt-3 text-sm ${className}`.trim();
                checkoutMessage.textContent = message;
            }

            function escapeHtml(value) {
                return value.replaceAll('&', '&amp;').replaceAll('<', '&lt;').replaceAll('>', '&gt;').replaceAll('"', '&quot;').replaceAll("'", '&#039;');
            }

            clearCartButton.addEventListener('click', () => { cart.clear(); setMessage('', ''); renderCart(); });
            productSearch.addEventListener('input', filterProducts);
            amountPaidInput.addEventListener('input', () => { updateBalance(); syncActionButtons(); });
            redeemedPointsInput.addEventListener('input', () => { updateBalance(); syncActionButtons(); });
            customerPhoneInput.addEventListener('input', queueCustomerLookup);
            customerNameInput.addEventListener('input', queueCustomerLookup);
            walkingCustomerInput.addEventListener('change', syncCustomerFields);
            printReceiptButton.addEventListener('click', () => checkout('print'));
            sendWhatsAppButton.addEventListener('click', () => checkout('whatsapp'));

            syncPaymentStyles();
            products.forEach((product) => syncProductCardState(product));
            syncCustomerFields();
            filterProducts();
            renderCart();
        })();
    </script>
@endsection
