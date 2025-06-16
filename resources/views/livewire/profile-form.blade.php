<div class="container my-5">
    <div class="card border-none">
        <div class="card-body">
            <h2 class="mb-4 fw-semibold fs-4">Profile Information</h2>
            <form wire:submit.prevent="save" enctype="multipart/form-data">
                <div class="row g-3">
                    {{-- NAME EN EMAIL --}}
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" wire:model="name" class="form-control" placeholder="Your full name" />
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email address</label>
                        <input type="email" wire:model="email" class="form-control" placeholder="you@example.com" />
                        @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- PROFILE PHOTO --}}
                    <div class="col-12 col-md-6">
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
                                <div wire:loading wire:target="new_profile_photo" class="text-info small mt-1">
                                    Uploading photo...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- SAVE CHANGES --}}
                <div class="mt-4 d-flex justify-content-start justify-content-md-end">
                    <button type="submit"
                            wire:loading.attr="disabled"
                            wire:loading.class="opacity-50"
                            wire:target="save,new_profile_photo"
                            class="btn btn-primary px-4 d-flex align-items-center gap-2">
                        <div wire:loading wire:target="save" class="spinner-border spinner-border-sm" role="status"></div>
                        <span wire:loading.remove wire:target="save">Save changes</span>
                        <span wire:loading wire:target="save">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MY ADDRESSES --}}
    <h4 class="mb-4 fw-semibold mt-5 mt-md-0">My Addresses</h4>

    <livewire:profile-addresses />

    <style>
        .card{
            border: none !important;
        }
        .card-body{
            padding: 0 !important;
        }
    </style>
</div>
