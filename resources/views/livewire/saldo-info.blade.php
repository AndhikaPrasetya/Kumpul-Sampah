
<div class="flex gap-3"  wire:poll.5s="updateSaldo">
    <!-- Saldo -->
    <div class="flex-1 bg-white/10 rounded-lg p-3 backdrop-blur-sm">
        <p class="text-xs text-white/80">Saldo</p>
        <p class="text-lg font-bold text-white">
            Rp {{$saldo}}</p>
    </div>
    <!-- Poin -->
    <div class="flex-1 bg-white/10 rounded-lg p-3 backdrop-blur-sm">
        <p class="text-xs text-white/80">Poin</p>
        <p class="text-lg font-bold text-white">
            {{ $points }}</p>
    </div>
</div>