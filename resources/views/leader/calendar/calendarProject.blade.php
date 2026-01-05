@extends('layouts.appW')

@section('contentW')
<section class="py-5 min-vh-100">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="display-5 fw-bold text-gradient">Projects & Tasks Calendar</h1>
            <a href="{{ route('leader.projects.index') }}" class="btn btn-outline-light">
                <i class="fas fa-arrow-left me-2"></i> Back to Projects
            </a>
        </div>
        <!-- Calendar -->
        @include("components._calendar")
    </div>
</section>

@endsection
