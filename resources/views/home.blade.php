@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!--div class="card">
                <div class="card-header">{{-- __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') --}}
                </div>
            </!--div-->


            <div class="card">
                <div class="card-header">
                    Dashboard
                </div>

                <div class="card-body">
                    <p>
                        Welcome <strong>{{ auth()->user()->name }}</strong>
                    </p>

                    <p>
                        Role : <span class="badge bg-primary">
                            {{ auth()->user()->role }}
                        </span>
                    </p>

                    @if(auth()->user()->isLeader())
                        <hr>
                        <p>Leader dashboard access enabled.</p>
                        <a href="#" class="btn btn-dark btn-sm">
                            Manage Teams
                        </a>
                    @endif

                    @if(auth()->user()->isMember())
                        <hr>
                        <p>Member dashboard access enabled.</p>
                        <a href="#" class="btn btn-success btn-sm">
                            View My Tasks
                        </a>
                    @endif

                    @if(auth()->user()->isAdmin())
                        <hr>
                        <p>Admin dashboard access enabled.</p>
                        <a href="#" class="btn btn-success btn-sm">
                            Admin Panel
                        </a>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
