<script>
if (typeof filterProducts === 'undefined') {
    function filterProducts(category, event) {
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.classList.remove('active', 'bg-black', 'text-white');
            btn.classList.add('bg-gray-100', 'text-black');
        });

        event.target.classList.add('active', 'bg-black', 'text-white');
        event.target.classList.remove('bg-gray-100', 'text-black');

        document.querySelectorAll('[data-categories]').forEach(product => {
            const categories = product.dataset.categories.split(' ');
            if (category === 'all' || categories.includes(category)) {
                product.classList.remove('hidden');
            } else {
                product.classList.add('hidden');
            }
        });
    }
}
</script>
