<div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <div class="flex h-full items-center">
        <main class="w-full max-w-md mx-auto p-6">
            <div class="mt-7 bg-white border border-gray-200 rounded-xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
                <div class="p-4 sm:p-7">
                    <div class="text-center">
                        <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">Reset password</h1>
                    </div>

                    <div class="mt-5">
                        <form wire:submit.prevent="save">
                            @if (session('status'))
                                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <div class="grid gap-y-4">
                                <!-- Email (readonly) -->
                                <div>
                                    <label class="block text-sm mb-2 dark:text-white">Email</label>
                                    <input type="email" value="{{ $email }}" disabled
                                           class="py-3 px-4 block w-full bg-gray-100 dark:bg-slate-800 border border-gray-200 rounded-lg text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400" />
                                    @error('email')
                                    <p class="text-red-600 mt-2" id="email-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div>
                                    <label for="password" class="block text-sm mb-2 dark:text-white">Password</label>
                                    <div class="relative">
                                        <input type="password" id="password" wire:model="password"
                                               autocomplete="new-password"
                                               aria-describedby="password-error"
                                               class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" />
                                    </div>
                                    @error('password')
                                    <p class="text-red-600 mt-2" id="password-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <label for="password_confirmation" class="block text-sm mb-2 dark:text-white">Confirm Password</label>
                                    <div class="relative">
                                        <input type="password" id="password_confirmation" wire:model="password_confirmation"
                                               autocomplete="new-password"
                                               aria-describedby="password_confirmation-error"
                                               class="py-3 px-4 block w-full border border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600" />
                                    </div>
                                    @error('password_confirmation')
                                    <p class="text-red-600 mt-2" id="password_confirmation-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Submit -->
                                <button type="submit"
                                        class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                    Save password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
