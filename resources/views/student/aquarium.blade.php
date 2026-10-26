<x-layouts.app title="My Aquarium Room — AquaClass">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="aquariumRoom()">

        <!-- Header Controls -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
            <div>
                <a href="{{ route('student.dashboard') }}" class="text-xs font-bold text-sky-700 hover:underline mb-1 inline-block">
                    ← Back to Dashboard
                </a>
                <h1 class="text-3xl font-black text-slate-900 flex items-center gap-2">
                    <span>🌊</span>
                    <span x-text="fishName + '\'s Underwater World'"></span>
                </h1>
            </div>

            <!-- Action buttons -->
            <div class="flex items-center gap-3">
                <button type="button" @click="openCustomizer = true" class="px-4 py-2.5 rounded-2xl bg-white border border-sky-300 text-sky-800 font-extrabold text-xs shadow-sm hover:bg-sky-50 transition flex items-center gap-2">
                    <span>🎨</span>
                    <span>Customize Fish</span>
                </button>
            </div>
        </div>

        <!-- Fullscreen Canvas Aquarium -->
        <div class="mb-8">
            <x-aquarium-canvas :fishes="[$fish]" height="500px" tankTitle="{{ $fish?->name ?? 'Finny' }}'s Habitat" />
        </div>

        <!-- Tank Decorations Shelf -->
        <div class="ocean-glass rounded-3xl p-6 sm:p-8 shadow-xl border border-sky-200">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🪸</span>
                    <div>
                        <h2 class="text-xl font-black text-slate-800">Your Aquarium Decorations</h2>
                        <p class="text-xs font-semibold text-slate-500">Decorate the reef with items unlocked with your homework points!</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-4">
                @foreach($allDecorations as $dec)
                    @php
                        $isUnlocked = $unlockedDecorations->contains('decoration_id', $dec->id)
                            || ($user->studentProfile?->total_points ?? 0) >= $dec->unlock_points;
                    @endphp
                    <div class="p-4 rounded-2xl border text-center transition flex flex-col justify-between {{ $isUnlocked ? 'bg-white border-sky-200 shadow-sm' : 'bg-slate-100/60 border-slate-200 opacity-50' }}">
                        <div>
                            <span class="text-3xl block mb-2">
                                @if($dec->type === 'treasure') 🪙
                                @elseif($dec->type === 'castle') 🏰
                                @elseif($dec->type === 'coral') 🪸
                                @elseif($dec->type === 'shell') 🐚
                                @else 🌿
                                @endif
                            </span>
                            <h4 class="text-xs font-extrabold text-slate-800 leading-tight mb-1">
                                {{ $dec->name }}
                            </h4>
                            <p class="text-[10px] text-slate-500 leading-tight line-clamp-2">
                                {{ $dec->description }}
                            </p>
                        </div>

                        <div class="mt-3 pt-2 border-t border-slate-100">
                            @if($isUnlocked)
                                <span class="inline-block px-2 py-0.5 rounded-md bg-emerald-100 text-emerald-800 text-[10px] font-black">
                                    ✓ Active in Tank
                                </span>
                            @else
                                <span class="text-[10px] font-bold text-slate-500">
                                    🔒 {{ $dec->unlock_points }} pts
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Customize Fish Slide-over Modal -->
        <div x-show="openCustomizer" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
            <div class="flex items-center justify-center min-h-screen p-4 bg-slate-900/60 backdrop-blur-sm">
                <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-sky-200 space-y-6" @click.away="openCustomizer = false">
                    
                    <div class="flex items-center justify-between border-b pb-4">
                        <h3 class="text-xl font-black text-slate-900 flex items-center gap-2">
                            <span>🎨</span>
                            <span>Fish Customizer</span>
                        </h3>
                        <button type="button" @click="openCustomizer = false" class="text-slate-400 hover:text-slate-600 text-xl font-bold">✕</button>
                    </div>

                    <!-- Fish Name -->
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wide mb-2">
                            Fish Name
                        </label>
                        <input type="text" x-model="fishName" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 font-bold text-slate-800">
                    </div>

                    <!-- Species -->
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wide mb-2">
                            Fish Species
                        </label>
                        <select x-model="species" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-cyan-500 font-bold text-slate-800">
                            <option value="clownfish">Clownfish (Striped Reef Swimmer) 🐠</option>
                            <option value="blue_tang">Blue Tang (Lagoon Explorer) 🐟</option>
                            <option value="yellow_tang">Yellow Tang (Sunlight Fin) 🐡</option>
                            <option value="angelfish">Angelfish (Regal Deep Sea) 🐬</option>
                        </select>
                    </div>

                    <!-- Color Picker -->
                    <div>
                        <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wide mb-2">
                            Fish Color
                        </label>
                        <div class="flex items-center gap-3">
                            <input type="color" x-model="fishColor" class="w-12 h-12 rounded-xl cursor-pointer border-0 p-0">
                            <span class="text-xs font-bold text-slate-600" x-text="fishColor"></span>
                        </div>
                    </div>

                    <!-- Action -->
                    <div class="pt-4 border-t flex items-center justify-end gap-3">
                        <button type="button" @click="openCustomizer = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-100">
                            Cancel
                        </button>
                        <button type="button" @click="saveCustomization()" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-extrabold text-xs shadow-md shadow-cyan-500/25 hover:scale-105 transition">
                            Save Changes ✨
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>

    @push('scripts')
    <script>
    function aquariumRoom() {
        return {
            openCustomizer: false,
            fishName: '{{ $fish?->name ?? "Finny" }}',
            species: '{{ $fish?->species ?? "clownfish" }}',
            fishColor: '{{ $fish?->color ?? "#FF7A00" }}',

            async saveCustomization() {
                try {
                    const res = await fetch("{{ route('student.fish.update') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            name: this.fishName,
                            species: this.species,
                            color: this.fishColor
                        })
                    });
                    const data = await res.json();
                    if (data.success) {
                        this.openCustomizer = false;
                        if (window.AquaAudio) window.AquaAudio.playChime('success');
                        window.location.reload();
                    }
                } catch (e) {
                    alert('Could not update fish settings.');
                }
            }
        };
    }
    </script>
    @endpush
</x-layouts.app>
