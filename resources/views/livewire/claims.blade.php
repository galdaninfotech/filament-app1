<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <form wire:submit="claim">
        <input type="text" wire:model="form.ticketId">
        <div>
            @error('form.ticketId') <span class="error">{{ $message }}</span> @enderror
        </div>
     
        <input type="text" wire:model="form.gamePrizeId">
        <div>
            @error('form.gamePrizeId') <span class="error">{{ $message }}</span> @enderror
        </div>
     
        <button type="submit">Claim Now</button>
    </form>
</div>
