@props(['dark' => false])
<span class="inline-flex items-center gap-2.5">
    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl brand-gradient text-ink shadow-lg shadow-brand/30">
        <x-icon name="car" class="h-6 w-6" :stroke="2" />
    </span>
    <span class="leading-tight">
        <span class="block text-lg font-extrabold {{ $dark ? 'text-white' : 'text-ink' }}">{{ __('Turath Cars') }}</span>
        <span class="block text-[10px] font-semibold tracking-wider {{ $dark ? 'text-brand-light' : 'text-brand-dark' }}">{{ __('USED CARS IN EGYPT') }}</span>
    </span>
</span>
