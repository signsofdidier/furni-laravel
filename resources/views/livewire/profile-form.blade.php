<div class="container my-5">
    <div class="card border-none">
        <div class="card-body">
            <h2 class="mb-4 fw-semibold fs-4">Profile Information</h2>

            {{-- SweetAlert flash message wordt automatisch afgehandeld via Livewire event --}}

            <form wire:submit="save" enctype="multipart/form-data">
                <div class="row g-3">
                    {{-- Name --}}
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" wire:model="name" class="form-control" placeholder="Your full name" />
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <label class="form-label">Email address</label>
                        <input type="email" wire:model="email" class="form-control" placeholder="you@example.com" />
                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Profile photo --}}
                    <div class="col-12">
                        <label class="form-label mb-2">Profile Photo</label>
                        <div class="d-flex align-items-center gap-4">
                            <div class="flex-shrink-0">
                                @if ($new_profile_photo)
                                    <img src="{{ $new_profile_photo->temporaryUrl() }}" alt="New photo" class="rounded-circle object-cover" style="width: 96px; height: 96px;">
                                @elseif ($profile_photo)
                                    <img src="{{ Storage::url($profile_photo) }}" alt="Current photo" class="rounded-circle object-cover" style="width: 96px; height: 96px;">
                                @else
                                    <img src="{{ asset('assets/img/default-avatar.png') }}" alt="Default photo" class="rounded-circle object-cover" style="width: 96px; height: 96px;">
                                @endif
                            </div>
                            <div>
                                <label for="photo-upload" class="btn btn-dark btn-sm">Choose File</label>
                                <input id="photo-upload" type="file" wire:model="new_profile_photo" class="d-none" />
                                <p class="form-text mt-1">Allowed formats: JPG, PNG — Max size: 2MB</p>
                                @error('new_profile_photo') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="mb-3 fw-semibold">Address Information</h5>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">First name</label>
                        <input type="text" wire:model="first_name" class="form-control">
                        @error('first_name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Last name</label>
                        <input type="text" wire:model="last_name" class="form-control">
                        @error('last_name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Phone number</label>
                        <input type="text" wire:model="phone" class="form-control">
                        @error('phone') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-6">
                        <label class="form-label">Street address</label>
                        <input type="text" wire:model="street_address" class="form-control">
                        @error('street_address') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">City</label>
                        <input type="text" wire:model="city" class="form-control">
                        @error('city') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">State / Province</label>
                        <input type="text" wire:model="state" class="form-control">
                        @error('state') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Postal code</label>
                        <input type="text" wire:model="zip_code" class="form-control">
                        @error('zip_code') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                </div>

                <div class="mt-4 d-flex justify-content-end">
                    <button type="submit"
                            wire:loading.attr="disabled"
                            wire:target="save"
                            class="btn btn-primary px-4 d-flex align-items-center gap-2">
                        <div wire:loading wire:target="save" class="spinner-border spinner-border-sm" role="status"></div>
                        <span wire:loading.remove wire:target="save">Save changes</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .card{
            border: none !important;
        }
        .card-body{
            padding: 0 !important;
        }
    </style>
</div>
