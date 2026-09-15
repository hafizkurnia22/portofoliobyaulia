@extends('layouts.app')

@section('title', 'Login Tryout CPNS')

@section('content')
    <section class="tryout-login-page">
        <div class="container">
            <div class="tryout-login-card" data-aos="fade-up">
                <div class="tryout-login-copy">
                    <span class="section-label">Tryout CPNS</span>
                    <h1>Masuk Tryout CPNS</h1>
                    <p>Gunakan username dan PIN yang sudah dibuat oleh admin untuk mulai mengerjakan soal.</p>
                </div>

                <form action="{{ route('tryout.login.submit') }}" method="POST" class="tryout-login-form">
                    @csrf

                    @if (session('success'))
                        <div class="alert alert-success rounded-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger rounded-4">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <div class="tryout-login-input">
                            <i class="bi bi-person"></i>
                            <input type="text" name="username" class="form-control"
                                value="{{ old('username') }}" placeholder="Masukkan username" required autofocus>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">PIN</label>
                        <div class="tryout-login-input">
                            <i class="bi bi-shield-lock"></i>
                            <input type="password" name="pin" class="form-control"
                                inputmode="numeric" placeholder="Masukkan PIN" required>
                        </div>
                    </div>

                    <button type="submit" class="cat-action-btn w-100 justify-content-center">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Masuk Tryout
                    </button>
                </form>
            </div>
        </div>
    </section>

    <script>
        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }

        window.addEventListener('load', function() {
            window.scrollTo({
                top: 0,
                left: 0,
                behavior: 'auto'
            });
        });
    </script>
@endsection
