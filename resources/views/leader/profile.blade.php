@extends('layouts.appW')

@section('contentW')
<section class="py-5 min-vh-100 d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card feature-card text-white shadow-lg border-0">
                    <div class="card-header bg-gradient-primary text-center py-4 fw-bold fs-4">
                        Mon Profil
                    </div>

                    <div class="card-body p-5">
                        <form method="POST" action="{{ route('leader.profile.update') }}" enctype="multipart/form-data">
                            @csrf @method('PUT')

                            <div class="text-center mb-5">
                                <img src="{{ Auth::user()->avatar ?? 'default-avatar.png' }}" class="rounded-circle mb-3" width="120" height="120">
                                <div class="mb-3">
                                    <input type="file" name="avatar" class="form-control form-control-lg" accept="image/*">
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Nom complet</label>
                                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}"
                                           class="form-control form-control-lg @error('name') is-invalid @enderror" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Email</label>
                                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                                           class="form-control form-control-lg @error('email') is-invalid @enderror" required>
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Position</label>
                                    <input type="text" name="position" value="{{ old('position', Auth::user()->position ?? '') }}"
                                           class="form-control form-control-lg @error('position') is-invalid @enderror">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Date de naissance</label>
                                    <input type="date" name="birthdate" value="{{ old('birthdate', Auth::user()->birthdate ?? '') }}"
                                           class="form-control form-control-lg @error('birthdate') is-invalid @enderror">
                                </div>
                            </div>

                            <div class="mt-5">
                                <h5 class="mb-3">Changer le mot de passe</h5>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Nouveau mot de passe</label>
                                        <input type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror">
                                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-medium">Confirmation</label>
                                        <input type="password" name="password_confirmation" class="form-control form-control-lg">
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid mt-5">
                                <button type="submit" class="btn btn-lg btn-contact text-white fw-bold">
                                    Enregistrer les modifications
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
