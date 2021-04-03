<footer class="pt-3 pb-6 text-sm text-gray-100 text-opacity-75 text-center">
    <div class="mx-auto inline-flex justify-center items-center border-b border-gray-300 border-opacity-50 pb-1 mb-5" style="min-width: 400px">
        <a href="{{ route('home') }}" class="p-4">Home</a>
        <a href="{{ route('frontend.page.default', ['page_slug'=>'privacy']) }}" class="p-4">Privacy Policy</a>
        <a href="{{ route('frontend.page.default', ['page_slug'=>'tos']) }}" class="p-4">Terms of Service</a>
    </div>
    <p class="text-xs text-opacity-50">&copy;2020 {{ config('app.name') }}</p>
</footer>