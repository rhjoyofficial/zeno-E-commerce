document.addEventListener('DOMContentLoaded', () => {
    const popup              = document.getElementById('product-cart-popup');
    if (!popup) return;

    const closeBtn           = document.getElementById('popup-close');
    const mainImage          = document.getElementById('popup-main-image');
    const thumbnailsContainer= document.getElementById('popup-thumbnails');
    const title              = document.getElementById('popup-title');
    const description        = document.getElementById('popup-description');
    const price              = document.getElementById('popup-price');
    const colorSection       = document.getElementById('popup-colors');
    const colorOptions       = document.getElementById('color-options');
    const sizeSection        = document.getElementById('popup-sizes');
    const sizeOptions        = document.getElementById('size-options');
    const features           = document.getElementById('popup-features');
    const qtyInput           = document.getElementById('popup-quantity');
    const popupAddToCartBtn  = document.getElementById('popup-add-to-cart');
    const maxChars           = 80;

    let selectedColor     = null;
    let selectedSize      = null;
    let selectedVariantId = null;
    let currentVariants   = [];

    document.getElementById('qty-decrease').addEventListener('click', () => {
        qtyInput.value = Math.max(1, parseInt(qtyInput.value) - 1);
    });
    document.getElementById('qty-increase').addEventListener('click', () => {
        qtyInput.value = Math.min(99, parseInt(qtyInput.value) + 1);
    });

    document.querySelectorAll('.add-to-cart').forEach((button) => {
        button.addEventListener('click', () => {
            selectedColor     = null;
            selectedSize      = null;
            selectedVariantId = null;
            qtyInput.value    = 1;

            const data = {
                id:           button.dataset.productId,
                title:        button.dataset.title || '',
                short_description: button.dataset.description || '',
                price:        parseFloat(button.dataset.price) || 0,
                discount_price: button.dataset.discountPrice ? parseFloat(button.dataset.discountPrice) : null,
                has_variants: button.dataset.hasVariants === 'true',
                images:       JSON.parse(button.dataset.images || '[]'),
                variants:     JSON.parse(button.dataset.variants || '[]'),
            };

            currentVariants = data.variants;

            title.textContent = data.title;
            const text = data.short_description || '';
            description.textContent = text.length > maxChars ? text.slice(0, maxChars) + '…' : text;

            const sym = (window.appConfig && window.appConfig.currencySymbol) ? window.appConfig.currencySymbol : '৳';
            const fmt = (n) => sym + parseFloat(n).toFixed(2);

            price.innerHTML = '';
            if (data.discount_price != null) {
                price.textContent = fmt(data.discount_price);
                const original = document.createElement('span');
                original.className = 'ml-2 text-gray-700 text-sm line-through';
                original.textContent = fmt(data.price);
                price.appendChild(original);
            } else {
                price.textContent = fmt(data.price);
            }

            thumbnailsContainer.innerHTML = '';
            if (data.images.length > 0) {
                const primary = data.images.find((img) => img.is_primary) || data.images[0];
                mainImage.src = primary.path;

                data.images.forEach((img) => {
                    const thumb = document.createElement('img');
                    thumb.src = img.path;
                    thumb.className = 'w-20 h-20 object-cover border cursor-pointer';
                    thumb.addEventListener('click', () => { mainImage.src = img.path; });
                    thumbnailsContainer.appendChild(thumb);
                });
            }

            if (data.has_variants && data.variants.length > 0) {
                features.classList.add('hidden');

                const colors = [...new Map(
                    data.variants.filter((v) => v.color_id).map((v) => [v.color_id, v])
                ).values()];

                if (colors.length > 0) {
                    colorSection.classList.remove('hidden');
                    colorOptions.innerHTML = '';
                    colors.forEach((c) => {
                        const btn = document.createElement('button');
                        btn.className = 'w-7 h-7 border-2 border-gray-300 rounded-full';
                        btn.style.background = c.color_hex;
                        btn.title = c.color_name;
                        btn.dataset.colorId = c.color_id;
                        btn.addEventListener('click', () => {
                            colorOptions.querySelectorAll('button').forEach((b) =>
                                b.classList.remove('border-black', 'ring-2', 'ring-black', 'border-gray-300')
                            );
                            btn.classList.add('border-black', 'ring-2', 'ring-black');
                            selectedColor = { id: c.color_id, name: c.color_name, hex: c.color_hex };
                            updateSizeOptions(selectedColor.id);
                            findMatchingVariant();
                        });
                        colorOptions.appendChild(btn);
                    });
                } else {
                    colorSection.classList.add('hidden');
                }

                const sizes = [...new Map(
                    data.variants.filter((v) => v.size_id).map((v) => [v.size_id, v])
                ).values()];

                if (sizes.length > 0) {
                    sizeSection.classList.remove('hidden');
                    renderSizeButtons(sizeOptions, sizes);
                } else {
                    sizeSection.classList.add('hidden');
                }
            } else {
                features.classList.remove('hidden');
                colorSection.classList.add('hidden');
                sizeSection.classList.add('hidden');
            }

            popupAddToCartBtn.dataset.productId = data.id;
            popup.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    });

    function renderSizeButtons(container, sizes) {
        container.innerHTML = '';
        sizes.forEach((s) => {
            const btn = document.createElement('button');
            btn.className = 'px-1 py-1 border-2 border-gray-300 text-sm font-medium';
            btn.textContent = s.size_name;
            btn.dataset.sizeId = s.size_id;
            btn.addEventListener('click', () => {
                container.querySelectorAll('button').forEach((b) =>
                    b.classList.remove('border-black', 'bg-black', 'text-white')
                );
                btn.classList.add('border-black', 'bg-black', 'text-white');
                selectedSize = { id: s.size_id, name: s.size_name };
                findMatchingVariant();
            });
            container.appendChild(btn);
        });
    }

    function updateSizeOptions(colorId) {
        const available = [...new Map(
            currentVariants.filter((v) => v.color_id === colorId && v.size_id)
                .map((v) => [v.size_id, v])
        ).values()];
        renderSizeButtons(sizeOptions, available);
        selectedSize = null;
    }

    function findMatchingVariant() {
        if (!selectedColor && !selectedSize) { selectedVariantId = null; return; }
        const match = currentVariants.find((v) => {
            const colorOk = selectedColor ? v.color_id === selectedColor.id : true;
            const sizeOk  = selectedSize  ? v.size_id  === selectedSize.id  : true;
            return colorOk && sizeOk;
        });
        selectedVariantId = match ? match.id : null;
        if (match) {
            const sym2    = (window.appConfig && window.appConfig.currencySymbol) ? window.appConfig.currencySymbol : '৳';
            const fmt2    = (n) => sym2 + parseFloat(n).toFixed(2);
            const hasDisc = match.final_price != null && match.final_price < match.price;
            price.innerHTML = '';
            if (hasDisc) {
                price.textContent = fmt2(match.final_price);
                const original = document.createElement('span');
                original.className = 'ml-2 text-gray-700 text-sm line-through';
                original.textContent = fmt2(match.price);
                price.appendChild(original);
            } else {
                price.textContent = fmt2(match.final_price ?? match.price);
            }
        }
    }

    function closePopup() {
        popup.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    closeBtn.addEventListener('click', closePopup);
    popup.addEventListener('click', (e) => { if (e.target === popup) closePopup(); });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !popup.classList.contains('hidden')) closePopup();
    });

    popupAddToCartBtn.addEventListener('click', () => {
        const productId = popupAddToCartBtn.dataset.productId;
        const qty       = qtyInput.value;

        if (currentVariants.length > 0) {
            if (!selectedColor) {
                if (typeof notifications !== 'undefined') notifications.error('Please select a color');
                return;
            }
            if (!selectedSize) {
                if (typeof notifications !== 'undefined') notifications.error('Please select a size');
                return;
            }
        }

        showLoader();
        fetch(window.appConfig.routes.cartAdd, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.appConfig.csrfToken,
            },
            body: JSON.stringify({ product_id: productId, qty, variant_id: selectedVariantId }),
        })
            .then((r) => r.json())
            .then((data) => {
                if (data.success) {
                    if (typeof notifications !== 'undefined') notifications.success('Product added to cart!');
                    document.querySelectorAll('.cart-counter').forEach((el) => { el.textContent = data.cart_count; });
                    closePopup();
                } else {
                    if (typeof notifications !== 'undefined') notifications.error('Error adding product to cart');
                }
            })
            .catch(() => {
                if (typeof notifications !== 'undefined') notifications.error('Error adding product to cart');
            })
            .finally(() => hideLoader());
    });
});
