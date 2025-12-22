<div class="bg-linear-to-b min-h-screen from-slate-50 via-white to-sky-50">
    <div class="mx-auto flex max-w-5xl flex-col gap-8 px-4 py-10 sm:px-6 lg:px-8">
        <header class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div class="space-y-1">
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-sky-700">Tailwind Merge</p>
                <h1 class="text-2xl font-semibold text-slate-900">See conflicting classes melt into one clear set</h1>
                <p class="text-sm text-slate-600">Layer component defaults with call-time overrides—last entry wins inside each field and across them.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <span
                    class="rounded-full bg-white px-3 py-1 text-xs font-medium text-slate-700 shadow-sm ring-1 ring-slate-200"
                >Livewire</span>
                <span
                    class="rounded-full bg-white px-3 py-1 text-xs font-medium text-slate-700 shadow-sm ring-1 ring-slate-200"
                >Tailwind v4 aware</span>
            </div>
        </header>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="space-y-4 rounded-2xl border border-slate-200/80 bg-white/90 p-6 shadow-sm">
                <div class="flex items-center justify-between gap-2">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Two layers, last wins</p>
                        <p class="text-xs text-slate-500">Base component classes first; call-time overrides second.</p>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-semibold uppercase tracking-[0.15em] text-slate-600">The component</label>
                    <textarea
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-3 text-sm text-slate-800 shadow-inner focus:border-sky-300 focus:outline-none focus:ring-2 focus:ring-sky-200"
                        wire:model.live.debounce.250ms="original"
                        spellcheck="false"
                        rows="3"
                    ></textarea>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-semibold uppercase tracking-[0.15em] text-slate-600">The component call</label>
                    <textarea
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-3 text-sm text-slate-800 shadow-inner focus:border-sky-300 focus:outline-none focus:ring-2 focus:ring-sky-200"
                        wire:model.live.debounce.250ms="override"
                        spellcheck="false"
                        rows="3"
                    ></textarea>
                    <p class="text-xs text-slate-500">Later classes inside a field win; call-time overrides beat component defaults.</p>
                </div>

            </div>

            <div class="flex flex-col gap-4 rounded-2xl border border-slate-200/80 bg-white/90 p-6 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">Merged classes</p>
                        <p class="text-xs text-slate-500">Pulled from <code
                                class="rounded bg-slate-100 px-1">getMergedProperty()</code>.</p>
                    </div>
                    <span
                        class="rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700 ring-1 ring-sky-100"
                    >Live updated</span>
                </div>
                <textarea
                    class="h-48 w-full resize-none rounded-xl border border-slate-200 bg-slate-50 px-3 py-3 text-xs font-mono text-slate-800 shadow-inner focus:outline-none"
                    rows="6"
                    disabled
                >{{ $this->merged }}</textarea>

                <div class="grid gap-3 sm:grid-cols-2">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 shadow-inner">
                        <p class="text-xs font-semibold uppercase tracking-[0.15em] text-slate-600">Component input</p>
                        <p class="mt-2 text-xs text-slate-700">{{ $original }}</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 shadow-inner">
                        <p class="text-xs font-semibold uppercase tracking-[0.15em] text-slate-600">Call input</p>
                        <p class="mt-2 text-xs text-slate-700">{{ $override }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
