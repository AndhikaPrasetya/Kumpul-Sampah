<div class="balance" wire:poll.5s="updateSaldo">
    <div>
        <span class="title">Saldo</span>
        <h1 class="total">RP {{$saldo }}</h1>
        <a href="#" class="badge badge-warning">
            {{ $points }} ></a>
    </div>
</div>