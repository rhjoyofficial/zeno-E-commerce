document.addEventListener('DOMContentLoaded', function () {
    const checkoutForm       = document.getElementById('checkoutForm');
    const selectedItemsInput = document.getElementById('selectedItemsInput');
    const cartContainer      = document.getElementById('cart-container');
    const orderValueTotalEl  = document.getElementById('order-value-total');
    const totalDiscountEl    = document.getElementById('total-discount');
    const parentDiscount     = document.querySelector('.parentDiscount');
    const vatAmountEl        = document.getElementById('vat-amount');
    const totalPriceEl       = document.getElementById('total-price');

    if (!cartContainer) return;

    function updateCartTotals() {
        let orderValue    = 0;
        let totalDiscount = 0;
        const vatRate     = (window.appConfig && window.appConfig.vatRate != null) ? window.appConfig.vatRate : 0.05;
        const sym         = (window.appConfig && window.appConfig.currencySymbol) ? window.appConfig.currencySymbol : '৳';

        document.querySelectorAll('.cart-item-row').forEach((row) => {
            const checkbox = row.querySelector('.item-checkbox');
            if (checkbox && checkbox.checked) {
                const price    = parseFloat(row.dataset.price);
                const discount = parseFloat(row.dataset.discount);
                const qty      = parseInt(row.querySelector('.product-qty-selector').value);
                orderValue    += price * qty;
                totalDiscount += discount * qty;
            }
        });

        const vat   = orderValue * vatRate;
        const total = orderValue + vat;
        const fmt   = (n) => n.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        if (orderValueTotalEl) orderValueTotalEl.textContent = `${sym}${fmt(orderValue)}`;
        if (vatAmountEl)       vatAmountEl.textContent       = `${sym}${fmt(vat)}`;
        if (totalPriceEl)      totalPriceEl.textContent      = `${sym}${fmt(total)}`;

        if (totalDiscountEl && parentDiscount) {
            if (totalDiscount > 0) {
                totalDiscountEl.textContent = `- ${sym}${fmt(totalDiscount)}`;
                parentDiscount.classList.remove('hidden');
            } else {
                parentDiscount.classList.add('hidden');
            }
        }
    }

    document.querySelectorAll('.item-checkbox').forEach((cb) => {
        cb.addEventListener('change', updateCartTotals);
    });

    document.querySelectorAll('.product-qty-selector').forEach((sel) => {
        sel.addEventListener('change', function () {
            updateCartItem(this.getAttribute('data-cart-item-id'), this.value);
        });
    });

    document.querySelectorAll('.remove-item').forEach((btn) => {
        btn.addEventListener('click', function () {
            const itemId = this.getAttribute('data-item-id');
            if (itemId) removeCartItem(itemId);
        });
    });

    if (checkoutForm) {
        checkoutForm.addEventListener('submit', function () {
            const selectedIds = [];
            document.querySelectorAll('.cart-item-row .item-checkbox:checked').forEach((cb) => {
                selectedIds.push(cb.closest('.cart-item-row').dataset.itemId);
            });
            selectedItemsInput.value = JSON.stringify(selectedIds);
        });
    }

    function removeCartItem(itemId) {
        fetch(`/cart/remove/${itemId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.appConfig.csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
        })
            .then((r) => { if (!r.ok) throw new Error(); return r.json(); })
            .then((data) => {
                if (data.success) {
                    const row = document.querySelector(`.cart-item-row[data-item-id="${itemId}"]`);
                    if (row) { row.remove(); updateCartTotals(); }

                    document.querySelectorAll('.cart-counter').forEach((el) => { el.textContent = data.cart_count; });

                    if (data.cart_count === 0) {
                        cartContainer.innerHTML = `<div class="flex-1 text-center py-12">
                            <h2 class="text-3xl font-bold py-4">Your bag is empty</h2>
                            <a href="${window.appConfig.routes.home}" class="bg-black text-lg text-white px-6 py-3 mt-4 inline-block">Continue Shopping</a>
                        </div>`;
                    }

                    const hr = document.querySelector(`hr[data-hr-id="${itemId}"]`);
                    if (hr) hr.remove();
                } else {
                    if (typeof notifications !== 'undefined') notifications.error(data.message || 'Failed to remove item.');
                }
            })
            .catch(() => {
                if (typeof notifications !== 'undefined') notifications.error('An error occurred while removing the item.');
            });
    }

    function updateCartItem(itemId, newQty) {
        fetch(`/cart/update/${itemId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': window.appConfig.csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ qty: newQty }),
        })
            .then((r) => { if (!r.ok) throw new Error(); return r.json(); })
            .then((data) => {
                if (data.success) {
                    document.querySelectorAll('.cart-counter').forEach((el) => { el.textContent = data.cart_count; });
                    const badge = document.querySelector('.itemInBag');
                    if (badge) badge.textContent = `(${data.cart_count} ${data.cart_count <= 1 ? 'item' : 'items'})`;
                    updateCartTotals();
                } else {
                    if (typeof notifications !== 'undefined') notifications.error(data.message || 'Failed to update cart.');
                }
            })
            .catch(() => {
                if (typeof notifications !== 'undefined') notifications.error('An error occurred. Please try again.');
            });
    }

    updateCartTotals();
});
