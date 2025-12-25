@extends('winHome')

@section('contentH')
<section id="register" class="py-5 min-vh-100 d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-7 col-xl-6">
                <div class="card feature-card text-white shadow-lg border-0">
                    <div class="card-header text-center py-4 bg-gradient-primary fw-bold fs-3">
                        {{ __('Register') }}
                    </div>

                    <div class="card-body p-5">
                        <form method="POST" action="{{ route('register.form') }}">
                            @csrf

                            <!-- Name -->
                            <div class="row mb-4 align-items-center">
                                <label for="name" class="col-md-4 col-form-label text-md-end fw-medium">
                                    {{ __('Name') }}
                                </label>
                                <div class="col-md-8">
                                    <input id="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror"
                                           name="name" value="{{ old('name') }}" placeholder="Your Full Name" required autocomplete="name" autofocus>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="row mb-4 align-items-center">
                                <label for="email" class="col-md-4 col-form-label text-md-end fw-medium">
                                    {{ __('Email Address') }}
                                </label>
                                <div class="col-md-8">
                                    <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                                           name="email" value="{{ old('email') }}" placeholder="email@example.com" required autocomplete="email">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Password -->
                            <div class="row mb-4 align-items-center">
                                <label for="password" class="col-md-4 col-form-label text-md-end fw-medium">
                                    {{ __('Password') }}
                                </label>
                                <div class="col-md-8">
                                    <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                                           name="password" placeholder="At least 8 characters" required autocomplete="new-password">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="row mb-4 align-items-center">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-end fw-medium">
                                    {{ __('Confirm Password') }}
                                </label>
                                <div class="col-md-8">
                                    <input id="password-confirm" type="password" class="form-control form-control-lg"
                                           name="password_confirmation" placeholder="Repeat your password" required autocomplete="new-password">
                                </div>
                            </div>

                            <!-- Role -->
                            <div class="row mb-5 align-items-center">
                                <label for="role" class="col-md-4 col-form-label text-md-end fw-medium">
                                    {{ __('Role') }}
                                </label>
                                <div class="col-md-8">
                                    <select id="role" name="role" class="form-select form-select-lg @error('role') is-invalid @enderror" required>
                                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>-- Choose your role --</option>
                                        <option value="leader" {{ old('role') == 'leader' ? 'selected' : '' }}>Leader</option>
                                        <option value="member" {{ old('role') == 'member' ? 'selected' : '' }}>Member</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Position & Birthdate (sur la même ligne sur écrans moyens et plus) -->
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="position" class="form-label fw-medium ms-3 ms-md-0">
                                        {{ __('Position') }}
                                    </label>
                                    <input id="position" type="text" class="form-control form-control-lg @error('position') is-invalid @enderror"
                                           name="position" value="{{ old('position') }}" placeholder="e.g. Developer, Designer..." required>
                                    @error('position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="birthdate" class="form-label fw-medium ms-3 ms-md-0">
                                        {{ __('Birthdate') }}
                                    </label>
                                    <input id="birthdate" type="date" class="form-control form-control-lg @error('birthdate') is-invalid @enderror"
                                           name="birthdate" value="{{ old('birthdate') }}" required>
                                    @error('birthdate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="row">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-lg btn-contact text-white px-5 py-3 fw-bold w-100">
                                        {{ __('Register') }}
                                    </button>
                                </div>
                            </div>

                            <!-- Lien vers Login -->
                            <div class="text-center mt-4">
                                <p class="text-gray-300">
                                    Already have an account?
                                    <a href="{{ route('login.form') }}" class="text-info fw-medium hover:underline">
                                        Login here
                                    </a>
                                </p>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
