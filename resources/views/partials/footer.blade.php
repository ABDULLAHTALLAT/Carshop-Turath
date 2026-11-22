<footer class="relative mt-20 overflow-hidden bg-navy text-white">
    <svg class="pointer-events-none absolute inset-x-0 top-0 h-3 w-full text-white dark:text-[#0b1220]" viewBox="0 0 1200 12" preserveAspectRatio="none" aria-hidden="true"><path fill="currentColor" d="M0 0h1200v4c-120 6-240 8-360 6S600 2 480 4 120 12 0 6Z"/></svg>
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 md:grid-cols-2 lg:grid-cols-4">
        <div>
            <x-logo :dark="true" />
            <p class="mt-4 max-w-xs text-sm leading-7 text-white/65">{{ __('An independent broker connecting car owners and buyers across Egypt. We never own the cars we list.') }}</p>
            <a href="https://wa.me/{{ preg_replace('/\D/', '', config('turath.broker.whatsapp')) }}" target="_blank" rel="noopener noreferrer" class="btn btn-wa btn-sm mt-5"><x-icon name="whatsapp" class="h-4 w-4" /> {{ __('Chat on WhatsApp') }}</a>
        </div>
        <div>
            <h3 class="mb-4 text-sm font-bold text-white">{{ __('Explore') }}</h3>
            <ul class="space-y-2.5 text-sm text-white/65">
                <li><a class="inline-flex items-center gap-2 hover:text-brand" href="{{ route('shop') }}"><x-icon name="car" class="h-4 w-4" /> {{ __('Shop') }}</a></li>
                <li><a class="inline-flex items-center gap-2 hover:text-brand" href="{{ route('about') }}"><x-icon name="users" class="h-4 w-4" /> {{ __('About') }}</a></li>
                <li><a class="inline-flex items-center gap-2 hover:text-brand" href="{{ route('contact') }}"><x-icon name="mail" class="h-4 w-4" /> {{ __('Contact') }}</a></li>
            </ul>
        </div>
        <div>
            <h3 class="mb-4 text-sm font-bold text-white">{{ __('Popular searches') }}</h3>
            <ul class="space-y-2.5 text-sm text-white/65">
                <li><a class="hover:text-brand" href="{{ route('shop', ['body_type' => 'SUV']) }}">{{ __('SUVs') }}</a></li>
                <li><a class="hover:text-brand" href="{{ route('shop', ['governorate' => 'Cairo']) }}">{{ __('Cars in Cairo') }}</a></li>
                <li><a class="hover:text-brand" href="{{ route('shop', ['max_price' => 500000]) }}">{{ __('Under 500,000 EGP') }}</a></li>
                <li><a class="hover:text-brand" href="{{ route('shop', ['license_max_age' => 3]) }}">{{ __('Licensed in the last 3 years') }}</a></li>
            </ul>
        </div>
        <div>
            <h3 class="mb-4 text-sm font-bold text-white">{{ __('Get in touch') }}</h3>
            <ul class="space-y-3 text-sm text-white/65">
                <li class="flex items-center gap-2.5"><x-icon name="phone" class="h-4 w-4 text-brand" /> <span dir="ltr" class="num">{{ config('turath.broker.phone') }}</span></li>
                <li class="flex items-center gap-2.5"><x-icon name="mail" class="h-4 w-4 text-brand" /> {{ config('turath.broker.email') }}</li>
                <li class="flex items-center gap-2.5"><x-icon name="clock" class="h-4 w-4 text-brand" /> {{ __('Sat - Thu, 10:00 - 20:00') }}</li>
                <li class="flex items-center gap-2.5"><x-icon name="pin" class="h-4 w-4 text-brand" /> {{ __('Cairo, Egypt') }}</li>
            </ul>
        </div>
    </div>
    <div class="border-t border-white/10">
        <p class="mx-auto max-w-7xl px-4 py-5 text-xs text-white/50">&copy; {{ date('Y') }} {{ __('Turath Cars') }}. {{ __('We act as an independent broker only; we do not own the listed cars.') }}</p>
    </div>
</footer>
