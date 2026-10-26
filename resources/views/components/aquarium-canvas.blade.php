@props([
    'fishes' => [], // array or collection of fish objects
    'decorations' => [],
    'height' => '420px',
    'interactive' => true,
    'showControls' => true,
    'tankTitle' => 'Virtual Classroom Tank'
])

@php
    // Prepare fish data array for JavaScript
    $fishArray = [];
    foreach ($fishes as $f) {
        $fishArray[] = [
            'id' => $f->id ?? 1,
            'name' => $f->name ?? 'Fish',
            'species' => $f->species ?? 'clownfish',
            'color' => $f->color ?? '#FF7A00',
            'size' => floatval($f->size ?? 1.0),
            'stage' => $f->stage?->display_name ?? 'Swimmer',
            'studentName' => $f->user?->name ?? $f->name ?? 'Student',
        ];
    }
    if (empty($fishArray)) {
        $fishArray[] = [
            'id' => 1,
            'name' => 'Finny',
            'species' => 'clownfish',
            'color' => '#FF7A00',
            'size' => 1.2,
            'stage' => 'Reef Explorer',
            'studentName' => 'Leo Starfish',
        ];
    }
@endphp

<div class="relative w-full rounded-3xl overflow-hidden shadow-2xl border-4 border-sky-300/60 bg-gradient-to-b from-sky-400 via-cyan-600 to-blue-900 group" style="height: {{ $height }};" id="tank-wrapper">

    <!-- Top Water Caustics / Ripple Line -->
    <div class="absolute top-0 inset-x-0 h-4 bg-gradient-to-b from-white/40 to-transparent pointer-events-none z-10"></div>

    <!-- Live Aquarium Canvas -->
    <canvas id="aquarium-canvas" class="w-full h-full block cursor-pointer"></canvas>

    <!-- Overlay Controls & Status -->
    @if($showControls)
        <div class="absolute top-4 left-4 z-20 flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-2xl bg-slate-900/60 backdrop-blur-md text-white text-xs font-bold flex items-center gap-1.5 border border-white/20 shadow-md">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>{{ $tankTitle }}</span>
            </span>
            <span class="px-2.5 py-1 rounded-xl bg-white/20 backdrop-blur-md text-white text-[11px] font-semibold border border-white/10 hidden sm:inline">
                🐟 {{ count($fishArray) }} {{ count($fishArray) === 1 ? 'Fish Swimming' : 'Fish in Tank' }}
            </span>
        </div>

        <div class="absolute top-4 right-4 z-20 flex items-center gap-2">
            <!-- Feed Fish Button -->
            <button type="button" onclick="dropFishFood()" class="px-4 py-2 rounded-2xl bg-amber-400 hover:bg-amber-300 active:scale-95 text-amber-950 text-xs font-extrabold shadow-lg shadow-amber-500/30 flex items-center gap-1.5 transition duration-150">
                <span class="text-base">🦐</span>
                <span>Feed Fish</span>
            </button>
        </div>

        <div class="absolute bottom-3 left-4 z-20 text-[11px] font-semibold text-white/80 pointer-events-none drop-shadow">
            💡 Click anywhere in the tank to sprinkle fish treats!
        </div>
    @endif
</div>

@push('scripts')
<script>
(function() {
    const canvas = document.getElementById('aquarium-canvas');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');

    const fishesData = @json($fishArray);

    function resizeCanvas() {
        const rect = canvas.getBoundingClientRect();
        canvas.width = rect.width * window.devicePixelRatio;
        canvas.height = rect.height * window.devicePixelRatio;
        ctx.scale(window.devicePixelRatio, window.devicePixelRatio);
    }
    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();

    // Fish simulation class
    class FishActor {
        constructor(data, index, total) {
            this.data = data;
            const rect = canvas.getBoundingClientRect();
            this.x = (rect.width / (total + 1)) * (index + 1);
            this.y = rect.height * 0.4 + (Math.random() * 80 - 40);
            this.vx = (Math.random() * 1.2 + 0.6) * (Math.random() > 0.5 ? 1 : -1);
            this.vy = (Math.random() * 0.4 - 0.2);
            this.targetY = this.y;
            this.baseSize = 32 * (data.size || 1.0);
            this.color = data.color || '#FF7A00';
            this.tailPhase = Math.random() * Math.PI * 2;
            this.species = data.species || 'clownfish';
            this.hunger = 0;
        }

        update(width, height, foods) {
            this.tailPhase += 0.15;

            // Check for nearest food
            if (foods.length > 0) {
                let nearest = null;
                let minDist = 9999;
                for (let food of foods) {
                    let d = Math.hypot(food.x - this.x, food.y - this.y);
                    if (d < minDist) {
                        minDist = d;
                        nearest = food;
                    }
                }
                if (nearest && minDist < 350) {
                    let angle = Math.atan2(nearest.y - this.y, nearest.x - this.x);
                    this.vx = Math.cos(angle) * 2.2;
                    this.vy = Math.sin(angle) * 1.5;

                    // Eat food
                    if (minDist < 25) {
                        nearest.eaten = true;
                        if (window.AquaAudio) window.AquaAudio.playChime('pop');
                    }
                }
            } else {
                // Natural wander
                if (Math.random() < 0.02) {
                    this.targetY = 60 + Math.random() * (height - 120);
                }
                this.vy += (this.targetY - this.y) * 0.005;
                this.vy = Math.max(-0.8, Math.min(0.8, this.vy));

                if (Math.random() < 0.005) {
                    this.vx *= -1;
                }
            }

            this.x += this.vx;
            this.y += this.vy;

            // Boundaries
            const padding = 50;
            if (this.x < padding) {
                this.x = padding;
                this.vx = Math.abs(this.vx);
            } else if (this.x > width - padding) {
                this.x = width - padding;
                this.vx = -Math.abs(this.vx);
            }

            if (this.y < 50) this.y = 50;
            if (this.y > height - 60) this.y = height - 60;
        }

        draw(ctx) {
            ctx.save();
            ctx.translate(this.x, this.y);

            const facingRight = this.vx > 0;
            if (!facingRight) {
                ctx.scale(-1, 1);
            }

            const wiggle = Math.sin(this.tailPhase) * 6;
            const size = this.baseSize;

            // Fish Body Gradient
            const bodyGrad = ctx.createLinearGradient(-size * 0.8, 0, size * 0.8, 0);
            bodyGrad.addColorStop(0, this.color);
            bodyGrad.addColorStop(1, '#ffffff');

            // Draw Tail Fin
            ctx.fillStyle = this.color;
            ctx.beginPath();
            ctx.moveTo(-size * 0.7, 0);
            ctx.lineTo(-size * 1.25, -size * 0.45 + wiggle);
            ctx.lineTo(-size * 1.1, 0 + wiggle * 0.5);
            ctx.lineTo(-size * 1.25, size * 0.45 + wiggle);
            ctx.closePath();
            ctx.fill();

            // Draw Fish Body Oval
            ctx.fillStyle = this.color;
            ctx.beginPath();
            ctx.ellipse(0, 0, size * 0.75, size * 0.45, 0, 0, Math.PI * 2);
            ctx.fill();

            // Draw Species Stripes (Clownfish white stripes)
            if (this.species === 'clownfish') {
                ctx.fillStyle = 'rgba(255, 255, 255, 0.9)';
                ctx.beginPath();
                ctx.ellipse(-size * 0.15, 0, size * 0.12, size * 0.44, 0, 0, Math.PI * 2);
                ctx.fill();

                ctx.beginPath();
                ctx.ellipse(size * 0.25, 0, size * 0.09, size * 0.38, 0, 0, Math.PI * 2);
                ctx.fill();
            } else if (this.species === 'blue_tang') {
                // Blue tang dark oval mark
                ctx.fillStyle = '#0f172a';
                ctx.beginPath();
                ctx.ellipse(-size * 0.1, 0, size * 0.35, size * 0.22, 0, 0, Math.PI * 2);
                ctx.fill();
            }

            // Dorsal Fin
            ctx.fillStyle = this.color;
            ctx.beginPath();
            ctx.moveTo(-size * 0.3, -size * 0.4);
            ctx.quadraticCurveTo(0, -size * 0.7, size * 0.3, -size * 0.35);
            ctx.closePath();
            ctx.fill();

            // Pectoral Fin (wiggling)
            ctx.fillStyle = 'rgba(255, 255, 255, 0.75)';
            ctx.beginPath();
            ctx.ellipse(size * 0.1, size * 0.15, size * 0.2, size * 0.12, Math.PI / 4 + wiggle * 0.05, 0, Math.PI * 2);
            ctx.fill();

            // Eye
            ctx.fillStyle = '#ffffff';
            ctx.beginPath();
            ctx.arc(size * 0.45, -size * 0.12, size * 0.12, 0, Math.PI * 2);
            ctx.fill();
            ctx.fillStyle = '#0f172a';
            ctx.beginPath();
            ctx.arc(size * 0.48, -size * 0.12, size * 0.06, 0, Math.PI * 2);
            ctx.fill();

            ctx.restore();

            // Name & Stage Tag above fish
            ctx.save();
            ctx.translate(this.x, this.y - this.baseSize * 0.65 - 12);
            ctx.fillStyle = 'rgba(15, 23, 42, 0.75)';
            const text = this.data.name + ' (' + this.data.stage + ')';
            ctx.font = 'bold 11px Outfit, sans-serif';
            const metrics = ctx.measureText(text);
            const tagW = metrics.width + 14;
            ctx.beginPath();
            ctx.roundRect(-tagW / 2, -14, tagW, 20, 8);
            ctx.fill();

            ctx.fillStyle = '#ffffff';
            ctx.textAlign = 'center';
            ctx.fillText(text, 0, 0);
            ctx.restore();
        }
    }

    // Bubbles array
    const bubbles = [];
    for (let i = 0; i < 24; i++) {
        bubbles.push({
            x: Math.random() * 800,
            y: Math.random() * 400,
            radius: Math.random() * 5 + 2,
            speed: Math.random() * 0.9 + 0.4,
            wobble: Math.random() * Math.PI * 2
        });
    }

    // Food Pellets
    const foods = [];

    window.dropFishFood = function(x, y) {
        const rect = canvas.getBoundingClientRect();
        const dropX = x !== undefined ? x : rect.width * 0.5 + (Math.random() * 100 - 50);
        const dropY = y !== undefined ? y : 15;

        for (let i = 0; i < 4; i++) {
            foods.push({
                x: dropX + (Math.random() * 30 - 15),
                y: dropY + (Math.random() * 10),
                vy: Math.random() * 0.6 + 0.5,
                radius: Math.random() * 3 + 2.5,
                eaten: false
            });
        }
        if (window.AquaAudio) window.AquaAudio.playChime('splash');
    };

    canvas.addEventListener('click', function(e) {
        const rect = canvas.getBoundingClientRect();
        dropFishFood(e.clientX - rect.left, e.clientY - rect.top);
    });

    // Initialize fish instances
    const fishInstances = fishesData.map((fd, idx) => new FishActor(fd, idx, fishesData.length));

    // Seaweed decorations
    let plantTime = 0;

    function drawSeaweed(ctx, x, h, color) {
        ctx.save();
        ctx.strokeStyle = color;
        ctx.lineWidth = 10;
        ctx.lineCap = 'round';
        ctx.beginPath();
        ctx.moveTo(x, canvas.getBoundingClientRect().height);

        const segments = 5;
        const segH = h / segments;
        for (let s = 1; s <= segments; s++) {
            let sway = Math.sin(plantTime + s * 0.6 + x * 0.05) * (s * 4);
            ctx.lineTo(x + sway, canvas.getBoundingClientRect().height - segH * s);
        }
        ctx.stroke();
        ctx.restore();
    }

    // Animation Loop
    function render() {
        const rect = canvas.getBoundingClientRect();
        const width = rect.width;
        const height = rect.height;

        ctx.clearRect(0, 0, width, height);

        // Water background light rays
        ctx.save();
        const lightGrad = ctx.createLinearGradient(0, 0, width, height);
        lightGrad.addColorStop(0, 'rgba(56, 189, 248, 0.15)');
        lightGrad.addColorStop(1, 'rgba(3, 105, 161, 0.4)');
        ctx.fillStyle = lightGrad;
        ctx.fillRect(0, 0, width, height);
        ctx.restore();

        // Draw Sand Floor
        ctx.save();
        const sandGrad = ctx.createLinearGradient(0, height - 35, 0, height);
        sandGrad.addColorStop(0, '#fef08a');
        sandGrad.addColorStop(1, '#eab308');
        ctx.fillStyle = sandGrad;
        ctx.beginPath();
        ctx.ellipse(width * 0.5, height - 10, width * 0.6, 30, 0, 0, Math.PI * 2);
        ctx.fill();
        ctx.restore();

        // Draw Seaweeds
        plantTime += 0.03;
        drawSeaweed(ctx, 45, 110, 'rgba(16, 185, 129, 0.7)');
        drawSeaweed(ctx, 70, 140, 'rgba(5, 150, 105, 0.8)');
        drawSeaweed(ctx, width - 65, 130, 'rgba(16, 185, 129, 0.7)');
        drawSeaweed(ctx, width - 40, 160, 'rgba(4, 120, 87, 0.8)');

        // Draw Bubbles
        ctx.fillStyle = 'rgba(255, 255, 255, 0.45)';
        for (let b of bubbles) {
            b.y -= b.speed;
            b.wobble += 0.04;
            const wx = b.x + Math.sin(b.wobble) * 4;
            ctx.beginPath();
            ctx.arc(wx, b.y, b.radius, 0, Math.PI * 2);
            ctx.fill();
            if (b.y < -10) {
                b.y = height + 10;
                b.x = Math.random() * width;
            }
        }

        // Draw and update food
        for (let i = foods.length - 1; i >= 0; i--) {
            let f = foods[i];
            if (f.eaten || f.y > height - 20) {
                foods.splice(i, 1);
                continue;
            }
            f.y += f.vy;
            ctx.fillStyle = '#f59e0b';
            ctx.beginPath();
            ctx.arc(f.x, f.y, f.radius, 0, Math.PI * 2);
            ctx.fill();
        }

        // Update & Draw Fish
        for (let fish of fishInstances) {
            fish.update(width, height, foods);
            fish.draw(ctx);
        }

        requestAnimationFrame(render);
    }

    render();
})();
</script>
@endpush
