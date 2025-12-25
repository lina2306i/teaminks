@extends('winHome')

@section('contentH')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <h1 class="hero-title mb-4">Teaminks</h1>
            <p class="lead fs-4 text-gray-300 mb-5 px-3" style="max-width: 800px; margin: 0 auto;">
                "Empower your team, elevate your workflow.
                Team Link is the CRM for leaders and members to collaborate, analyze, and achieve more—together."
            </p>
            <div class="d-flex justify-content-center gap-4 flex-wrap">
                <a href="#features" class="btn btn-primary btn-lg btn-explore">Explore Features</a>
                <a href="#contact" class="btn btn-lg btn-contact text-white">Contact</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <h2 class="text-center display-4 fw-bold mb-5" style="background: linear-gradient(to right, #93c5fd, #3b82f6, #1d4ed8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                Features
            </h2>

            <div class="row g-5 justify-content-center">
                <!-- Leader Card -->
                <div class="col-md-6 col-lg-4">
                    <div class="card feature-card text-white h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-user-tie fa-2x text-info icon-bounce me-3"></i>
                                <h4 class="mb-0 fw-bold">Leader</h4>
                            </div>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-users text-info me-2"></i> Create teams & add members</li>
                                <li class="mb-2"><i class="fas fa-chart-bar text-purple me-2"></i> Get work analytics</li>
                                <li class="mb-2"><i class="fas fa-tasks text-success me-2"></i> Assign projects & tasks</li>
                                <li class="mb-2"><i class="fas fa-calendar-alt text-pink me-2"></i> Project timeline & calendar</li>
                                <li class="mb-2"><i class="fas fa-bell text-warning me-2"></i> Real-time notifications</li>
                                <li class="mb-2"><i class="fas fa-comments text-cyan me-2"></i> Create posts & interact</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Member Card -->
                <div class="col-md-6 col-lg-4">
                    <div class="card feature-card text-white h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-user fa-2x text-purple icon-bounce me-3"></i>
                                <h4 class="mb-0 fw-bold">Member</h4>
                            </div>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-users text-purple me-2"></i> Join teams</li>
                                <li class="mb-2"><i class="fas fa-check-square text-success me-2"></i> Finish assigned tasks</li>
                                <li class="mb-2"><i class="fas fa-trophy text-info me-2"></i> Gain points & feedback</li>
                                <li class="mb-2"><i class="fas fa-comments text-cyan me-2"></i> Interact with leader</li>
                                <li class="mb-2"><i class="fas fa-calendar text-pink me-2"></i> Notes & personal calendar</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Collaboration Card -->
                <div class="col-md-6 col-lg-4">
                    <div class="card collaboration-card text-white h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-handshake fa-2x text-pink icon-bounce me-3"></i>
                                <h4 class="mb-0 fw-bold">Collaboration</h4>
                            </div>
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-bell text-warning me-2"></i> Instant notifications</li>
                                <li class="mb-2"><i class="fas fa-comments text-cyan me-2"></i> Team communication</li>
                                <li class="mb-2"><i class="fas fa-calendar-alt text-pink me-2"></i> Project timelines</li>
                                <li class="mb-2"><i class="fas fa-chart-line text-purple me-2"></i> Performance analytics</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
