@extends('layouts.app')

@section('breadcrumb')
    Error
@endsection



@section('content')

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }

        .error-container {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .error-card {
            max-width: 650px;
            background-color: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .error-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            padding: 30px;
            text-align: center;
        }

        .error-code {
            font-size: 8rem;
            font-weight: 700;
            color: white;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
            margin: 0;
            line-height: 1;
        }

        .error-message {
            color: white;
            font-size: 1.5rem;
            margin-top: 0;
        }

        .error-body {
            padding: 40px;
            text-align: center;
        }

        .error-description {
            color: #6c757d;
            font-size: 1.1rem;
            margin-bottom: 25px;
        }

        .search-box {
            margin: 20px 0 30px;
        }

        .home-button {
            padding: 12px 30px;
            font-weight: 600;
        }
    </style>


    <div class="error-container">
        <div class="error-card">
            <div class="error-header">
                <h1 class="error-code">404</h1>
                <p class="error-message">Page Not Found</p>
            </div>

            <div class="error-body">
                <p class="error-description">
                    Oops! The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
                </p>

                {{-- <div class="search-box">
                    <form action="{{ route('search') }}" method="GET" class="d-flex">
                        <input type="text" name="query" class="form-control me-2" placeholder="Search for content...">
                        <button class="btn btn-outline-primary" type="submit">Search</button>
                    </form>
                </div> --}}

                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary home-button">Back to Homepage</a>
                    <a href="{{ url('https://lushanth.com/') }}" class="btn btn-outline-secondary">Contact Support</a>
                </div>

                <div class="mt-4">
                    <p class="mb-1">Here are some helpful links instead:</p>
                    <div class="d-flex justify-content-center gap-3 mt-2">
                        {{-- <a href="{{ url('about') }}" class="text-decoration-none">About</a>
                        <a href="{{ url('services') }}" class="text-decoration-none">Services</a>
                        <a href="{{ url('blog') }}" class="text-decoration-none">Blog</a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @endsection
