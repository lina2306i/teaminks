@extends('winHome')

@section('contentH')

<section id="login" class="py-5 min-vh-100 d-flex align-items-center">
    <div class="container">
        <div class="row g-5 justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card feature-card text-white h-100 border-0 shadow-lg rounded-3 overflow-hidden">

                    <div class="card-header text-center py-4 bg-gradient-primary fw-bold fs-3">
                        {{ __('Login') }}
                    </div>

                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('login.form') }}">
                            @csrf

                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="form-label text-xl text-white fw-medium">Email</label>

                                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email@example.com"
                                class="form-control form-control-lg bg-gray-600 border-0 text-white placeholder-gray-400 py-3 px-4 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" />

                                <x-input-error :messages="$errors->get('email')" />
                            </div>

                            <!-- Password -->
                             <div class="mb-4">
                                <label for="password" class="form-label text-xl text-white fw-medium">{{ __('Password') }}</label>

                                    <input id="password" type="password"  placeholder="••••••••" class="form-control  bg-gray-600 border-0  placeholder-gray-400
                                           py-3 px-4 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" name="password" required autocomplete="current-password">

                                    <x-input-error :messages="$errors->get('password')" />
                            </div>

                            <!-- Remember Me -->
                            <div class="mb-4 d-flex align-items-center form-check">
                                <input class="form-check-input"  style="width: 20px; height: 20px;" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label text-gray-400" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-lg fw-semibold py-3 rounded-lg d-flex justify-content-center align-items-center
                                           {{ $errors->any() || session('status') ? '' : '' }}
                                           bg-blue-600 hover:bg-blue-700 shadow-lg">
                                    @if (session('status'))
                                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                    @endif
                                    {{ __('Login') }}
                                </button>
                            </div>
                            <!-- Forgot Password Link -->
                            @if (Route::has('password.request'))
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection


{{-- Styles personnalisés pour matcher ton design Vue --}}
@push('styles')
<style>
    .text-xl { font-size: 1.25rem; }
    .bg-gradient-primary {
        background: linear-gradient(to right, #2563eb, #7c3aed);
    }
    .text-danger { color: #ef4444 !important; }
</style>
@endpush
