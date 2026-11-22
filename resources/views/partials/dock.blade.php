<div class="dock fixed bottom-5 end-5 z-30 flex flex-col items-center gap-3">
    <button type="button" data-to-top class="hidden h-11 w-11 items-center justify-center rounded-full bg-white text-ink shadow-xl ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:bg-brand" aria-label="{{ __('Back to top') }}"><x-icon name="arrow-up" class="h-5 w-5" /></button>
    <a href="https://wa.me/{{ preg_replace('/\D/', '', config('turath.broker.whatsapp')) }}" target="_blank" rel="noopener noreferrer" class="pulse-ring flex h-12 w-12 items-center justify-center rounded-full bg-[#25d366] text-[#06280f] shadow-xl transition hover:-translate-y-0.5" aria-label="WhatsApp"><x-icon name="whatsapp" class="h-6 w-6" :stroke="2" /></a>
</div>
