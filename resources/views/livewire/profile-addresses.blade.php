<div>
    <div class="mb-4">
        <button class="btn btn-primary" wire:click="showCreateForm">
            Add new address
        </button>
    </div>

    {{-- Formulier voor toevoegen/bewerken adres --}}
    @if($showForm)
        <div class="card my-4">
            <div class="card-body">
                <h5 class="mb-3 fw-semibold">{{ $edit_id ? 'Edit address' : 'Add new address' }}</h5>
                <form wire:submit.prevent="save">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <input wire:model="first_name" class="form-control" placeholder="First name">
                            @error('first_name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6">
                            <input wire:model="last_name" class="form-control" placeholder="Last name">
                            @error('last_name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6">
                            <input wire:model="phone" class="form-control" placeholder="Phone number">
                        </div>
                        <div class="col-md-6">
                            <input wire:model="street_address" class="form-control" placeholder="Street address">
                            @error('street_address') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-4">
                            <input wire:model="city" class="form-control" placeholder="City">
                            @error('city') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-4">
                            <input wire:model="state" class="form-control" placeholder="State/Province">
                            @error('state') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-4">
                            <input wire:model="zip_code" class="form-control" placeholder="Postal code">
                            @error('zip_code') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                    <div class="mt-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" wire:click="cancel">Cancel</button>
                        <button type="submit" class="btn btn-primary">{{ $edit_id ? 'Save' : 'Add address' }}</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Adressenlijst --}}
    @foreach($addresses as $adr)
        <div class="border rounded p-3 mb-3 d-flex justify-content-between align-items-center">
            <div>
                <strong>{{ $adr->first_name }} {{ $adr->last_name }}</strong>
                <br>{{ $adr->street_address }}, {{ $adr->zip_code }} {{ $adr->city }}<br>
                {{ $adr->state }} | {{ $adr->phone }}
            </div>
            <div>
                <button class="btn btn-sm btn-outline-secondary me-2" wire:click="edit({{ $adr->id }})">Edit</button>
                <button class="btn btn-sm btn-outline-danger" wire:click="delete({{ $adr->id }})">Delete</button>
            </div>
        </div>
    @endforeach

</div>
