@extends('layouts.app')

@section('title', 'Login Admin')

@section('content')

    <section class="admin-login-page">
        <div class="admin-login-wrapper">

            <div class="admin-login-left">
                <span class="admin-login-badge">
                    <i class="bi bi-shield-lock"></i>
                    Admin Access
                </span>

                <h1>Welcome Back</h1>
                <p>
                    Masuk ke dashboard untuk mengelola pengalaman, skill, sertifikasi,
                    dan data portfolio secara profesional.
                </p>

                <div class="admin-login-features">
                    <div>
                        <i class="bi bi-check-circle-fill"></i>
                        Kelola data portfolio
                    </div>
                    <div>
                        <i class="bi bi-check-circle-fill"></i>
                        Upload logo dan PDF
                    </div>
                    <div>
                        <i class="bi bi-check-circle-fill"></i>
                        Dashboard interaktif
                    </div>
                </div>
            </div>

            <div class="admin-login-card" data-aos="zoom-in">
                <div class="text-center mb-4">
                    <div class="admin-login-icon">
                        <i class="bi bi-person-lock"></i>
                    </div>

                    <h3>Login Admin</h3>
                    <p>Silakan masuk untuk melanjutkan.</p>
                </div>

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="/admin/login" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <div class="admin-input-group">
                            <i class="bi bi-envelope"></i>
                            <input type="email" name="email" class="form-control" placeholder="Masukkan email admin"
                                required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <div class="admin-input-group">
                            <i class="bi bi-lock"></i>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password"
                                required>
                        </div>
                    </div>

                    <button class="btn-admin-login" type="submit">
                        Login
                        <i class="bi bi-arrow-right"></i>
                    </button>
                </form>
            </div>

        </div>
    </section>

@endsection
