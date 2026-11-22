<div class="dock fixed bottom-5 end-5 z-30 flex flex-col items-center gap-3">
    <button type="button" data-to-top class="hidden h-11 w-11 items-center justify-center rounded-full bg-white text-ink shadow-xl ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:bg-brand dark:bg-[#111a2e] dark:text-white dark:ring-slate-700" aria-label="{{ __('Back to top') }}"><x-icon name="arrow-up" class="h-5 w-5" /></button>
    <button type="button" data-theme-toggle class="relative flex h-11 w-11 items-center justify-center rounded-full bg-navy text-white shadow-xl transition hover:-translate-y-0.5 hover:bg-navy-600" aria-label="{{ __('Toggle dark mode') }}">
        <span class="dock-sun"><x-icon name="sun" class="h-5 w-5" /></span><span class="dock-moon"><x-icon name="moon" class="h-5 w-5" /></span>
    </button>
    <a href="https://wa.me/{{ preg_replace('/\D/', '', config('turath.broker.whatsapp')) }}" target="_blank" rel="noopener noreferrer" class="pulse-ring flex h-12 w-12 items-center justify-center rounded-full bg-[#25d366] text-[#06280f] shadow-xl transition hover:-translate-y-0.5" aria-label="WhatsApp"><x-icon name="whatsapp" class="h-6 w-6" :stroke="2" /></a>
</div>
