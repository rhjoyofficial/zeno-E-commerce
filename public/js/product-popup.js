document.addEventListener("DOMContentLoaded", () => {
    const popup = document.getElementById("product-cart-popup");
    const closeBtn = document.getElementById("popup-close");
    const mainImage = document.getElementById("popup-main-image");
    const thumbnailsContainer = document.getElementById("popup-thumbnails");
    const title = document.getElementById("popup-title");
    const description = document.getElementById("popup-description");
    const price = document.getElementById("popup-price");
    const colorSection = document.getElementById("popup-colors");
    const colorOptions = document.getElementById("color-options");
    const sizeSection = document.getElementById("popup-sizes");
    const sizeOptions = document.getElementById("size-options");
    const features = document.getElementById("popup-features");
    const qtyInput = document.getElementById("popup-quantity");
    const popupAddToCartBtn = document.getElementById("popup-add-to-cart");
    const maxChars = 80;

    let selectedColor = null;
    let selectedSize = null;
    let selectedVariantId = null;
    let currentVariants = [];

    // Quantity controls
    document.getElementById("qty-decrease").addEventListener("click", () => {
        qtyInput.value = Math.max(1, parseInt(qtyInput.value) - 1);
    });
    document.getElementById("qty-increase").addEventListener("click", () => {
        qtyInput.value = Math.min(99, parseInt(qtyInput.value) + 1);
    });

    // Open popup on product button click
    document.querySelectorAll(".add-to-cart").forEach((button) => {
        button.addEventListener("click", () => {
            const productId = button.dataset.productId;

            // Reset selections
            selectedColor = null;
            selectedSize = null;
            selectedVariantId = null;
            qtyInput.value = 1;
            showLoader();
            fetch(window.appConfig.routes.productVariants, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": window.appConfig.csrfToken,
                },
                body: JSON.stringify({ product_id: productId }),
            })
                .then((res) => res.json())
                .then((data) => {
                    if (data.error) {
                        console.error(data.error);
                        if (typeof notifications !== "undefined") {
                            notifications.error(data.error);
                        }
                        return;
                    }

                    // Store variants for later use
                    currentVariants = data.variants || [];

                    // 🔹 Populate popup
                    title.textContent = data.title;
                    const text = data.short_description || "";
                    description.textContent = text.length > maxChars ? text.slice(0, maxChars) + "…" : text;
                    if (data.discount_price != null) {
                        price.textContent = "$" + data.discount_price;

                        const original = document.createElement("span");
                        original.className = "ml-2 text-gray-700 text-sm line-through";
                        original.textContent = "$" + data.price;

                        price.appendChild(original);
                    } else {
                        price.textContent = "$" + data.price;
                    }

                    // 🔹 Images
                    thumbnailsContainer.innerHTML = "";
                    if (data.images && data.images.length > 0) {
                        mainImage.src =
                            data.images.find((img) => img.is_primary)?.path ||
                            data.images[0].path;

                        data.images.forEach((img) => {
                            const thumb = document.createElement("img");
                            thumb.src = img.path;
                            thumb.className =
                                "w-20 h-20 object-cover border cursor-pointer";
                            thumb.addEventListener("click", () => {
                                mainImage.src = img.path;
                            });
                            thumbnailsContainer.appendChild(thumb);
                        });
                    }

                    // 🔹 Variants - Colors
                    if (data.has_variants && data.variants.length > 0) {
                        features.classList.add("hidden");
                        const colors = [
                            ...new Map(
                                data.variants
                                    .filter((v) => v.color_id)
                                    .map((v) => [v.color_id, v])
                            ).values(),
                        ];

                        if (colors.length > 0) {
                            colorSection.classList.remove("hidden");
                            colorOptions.innerHTML = "";
                            colors.forEach((c) => {
                                const btn = document.createElement("button");
                                btn.className =
                                    "w-7 h-7 border-2 border-gray-300 rounded-full";
                                btn.style.background = c.color_hex;
                                btn.title = c.color_name;
                                btn.dataset.colorId = c.color_id;
                                btn.dataset.colorName = c.color_name;
                                btn.addEventListener("click", () => {
                                    colorOptions
                                        .querySelectorAll("button")
                                        .forEach((b) =>
                                            b.classList.remove(
                                                "border-black",
                                                "ring-2",
                                                "ring-black",
                                                "focus:ring-2",
                                                "focus:ring-black",
                                                "border-gray-300"
                                            )
                                        );
                                    btn.classList.add(
                                        "border-black",
                                        "ring-2",
                                        "ring-black",
                                        "focus:ring-2",
                                        "focus:ring-black"
                                    );
                                    selectedColor = {
                                        id: c.color_id,
                                        name: c.color_name,
                                        hex: c.color_hex,
                                    };

                                    // Update available sizes based on selected color
                                    updateSizeOptions(selectedColor.id);

                                    // Find matching variant
                                    findMatchingVariant();
                                });
                                colorOptions.appendChild(btn);
                            });
                        } else {
                            colorSection.classList.add("hidden");
                        }

                        // 🔹 Variants - Sizes
                        const sizes = [
                            ...new Map(
                                data.variants
                                    .filter((v) => v.size_id)
                                    .map((v) => [v.size_id, v])
                            ).values(),
                        ];

                        if (sizes.length > 0) {
                            sizeSection.classList.remove("hidden");
                            sizeOptions.innerHTML = "";
                            sizes.forEach((s) => {
                                const btn = document.createElement("button");
                                btn.className =
                                    "px-1 py-1 border-2 border-gray-300 text-sm font-medium";
                                btn.textContent = s.size_name;
                                btn.dataset.sizeId = s.size_id;
                                btn.dataset.sizeName = s.size_name;
                                btn.addEventListener("click", () => {
                                    sizeOptions
                                        .querySelectorAll("button")
                                        .forEach((b) =>
                                            b.classList.remove(
                                                "border-black",
                                                "bg-black",
                                                "text-white"
                                            )
                                        );
                                    btn.classList.add(
                                        "border-black",
                                        "bg-black",
                                        "text-white"
                                    );
                                    selectedSize = {
                                        id: s.size_id,
                                        name: s.size_name,
                                    };

                                    // Find matching variant
                                    findMatchingVariant();
                                });
                                sizeOptions.appendChild(btn);
                            });
                        } else {
                            sizeSection.classList.add("hidden");
                        }
                    } else {
                        features.classList.remove("hidden");
                        colorSection.classList.add("hidden");
                        sizeSection.classList.add("hidden");
                    }

                    // Store product info in Add to Cart button
                    popupAddToCartBtn.dataset.productId = data.id;

                    // Show popup
                    popup.classList.remove("hidden");
                    document.body.style.overflow = "hidden";
                })
                .catch((err) => console.error("Error:", err))
                .finally(() => hideLoader());
        });
    });

    function updateSizeOptions(colorId) {
        // Filter sizes based on selected color
        const availableSizes = currentVariants
            .filter((v) => v.color_id === colorId)
            .map((v) => ({
                size_id: v.size_id,
                size_name: v.size_name,
            }));

        // Remove duplicates
        const uniqueSizes = [
            ...new Map(
                availableSizes.map((item) => [item.size_id, item])
            ).values(),
        ];

        // Update size options
        sizeOptions.innerHTML = "";
        uniqueSizes.forEach((s) => {
            const btn = document.createElement("button");
            btn.className =
                "px-3 py-2 border-2 border-gray-300 text-sm font-medium";
            btn.textContent = s.size_name;
            btn.dataset.sizeId = s.size_id;
            btn.dataset.sizeName = s.size_name;
            btn.addEventListener("click", () => {
                sizeOptions
                    .querySelectorAll("button")
                    .forEach((b) =>
                        b.classList.remove(
                            "border-black",
                            "bg-black",
                            "text-white"
                        )
                    );
                btn.classList.add("border-black", "bg-black", "text-white");
                selectedSize = {
                    id: s.size_id,
                    name: s.size_name,
                };

                // Find matching variant
                findMatchingVariant();
            });
            sizeOptions.appendChild(btn);
        });
    }

    function findMatchingVariant() {
        if (!selectedColor && !selectedSize) {
            selectedVariantId = null;
            return;
        }

        // Find variant that matches both color and size
        const matchingVariant = currentVariants.find((v) => {
            const colorMatch = selectedColor
                ? v.color_id === selectedColor.id
                : true;
            const sizeMatch = selectedSize
                ? v.size_id === selectedSize.id
                : true;
            return colorMatch && sizeMatch;
        });

        if (matchingVariant) {
            selectedVariantId = matchingVariant.id;
            // Update price if variant has different price
            if (matchingVariant.price) {
                price.textContent = matchingVariant.price + " $";
            }
        } else {
            selectedVariantId = null;
        }
    }

    // Close popup
    closeBtn.addEventListener("click", () => {
        popup.classList.add("hidden");
        document.body.style.overflow = "auto";
    });
    popup.addEventListener("click", (e) => {
        if (e.target === popup) {
            popup.classList.add("hidden");
            document.body.style.overflow = "auto";
        }
    });
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape" && !popup.classList.contains("hidden")) {
            popup.classList.add("hidden");
            document.body.style.overflow = "auto";
        }
    });

    // 🔹 Add to Cart from Popup
    popupAddToCartBtn.addEventListener("click", () => {
        const productId = popupAddToCartBtn.dataset.productId;
        const qty = qtyInput.value;

        // Validate variant selection for products with variants
        if (currentVariants.length > 0) {
            if (!selectedColor && !selectedSize) {
                if (typeof notifications !== "undefined") {
                    notifications.error("Please select a variant");
                }
                return;
            }

            if (!selectedColor) {
                if (typeof notifications !== "undefined") {
                    notifications.error("Please select a color");
                }
                return;
            }

            if (!selectedSize) {
                if (typeof notifications !== "undefined") {
                    notifications.error("Please select a size");
                }
                return;
            }
        }

        showLoader();
        fetch(window.appConfig.routes.cartAdd, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": window.appConfig.csrfToken,
            },
            body: JSON.stringify({
                product_id: productId,
                qty: qty,
                variant_id: selectedVariantId,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                console.log(data);
                if (data.success) {
                    if (typeof notifications !== "undefined") {
                        notifications.success("Product added to cart!");
                    }
                    document.querySelectorAll(".cart-counter").forEach((el) => {
                        el.textContent = data.cart_count;
                    });
                    popup.classList.add("hidden");
                    document.body.style.overflow = "auto";
                } else {
                    notifications.error("Error adding product to cart");
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                notifications.error("Error adding product to cart");
            })
            .finally(() => hideLoader());
    });
});
