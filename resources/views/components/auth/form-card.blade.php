@props(['title', 'subtitle' => null])

<div class="min-h-screen bg-gradient-to-r from-indigo-50 via-white to-indigo-50 flex items-center justify-center py-12 px-6">
    <div id="auth-form-card"
        style="opacity:0;transform:translateY(40px);transition:opacity 0.7s ease,transform 0.7s ease;"
        class="w-full mx-auto max-w-md bg-white -2xl shadow-lg p-8 space-y-6">

        {{-- Brand Icon --}}
        <a href="https://zeno.endbrackets.com/" title="Back to Home Page" class="flex justify-center mb-6 p-3">
            <img class="h-6 w-auto" src="{{ asset('images/Zeno.png') }}" alt="Brand Logo">
        </a>

        {{-- Title and Subtitle --}}
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-gray-900">
                {{ $title }}
            </h2>
            @isset($subtitle)
            <p class="mt-2 text-sm text-gray-500">
                {{ $subtitle }}
            </p>
            @endisset
        </div>

        <div>
            {{ $slot }}
        </div>
    </div>

</div>

<script>
(function () {
    var card = document.getElementById('auth-form-card');
    if (!card) return;
    setTimeout(function () {
        card.style.opacity = '1';
        card.style.transform = 'translateY(0)';
    }, 100);
})();
</script>
