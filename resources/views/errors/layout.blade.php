<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - GroceryStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .error-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }
        .error-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            overflow: hidden;
            transition: transform 0.3s;
        }
        .error-card:hover {
            transform: translateY(-5px);
        }
        .error-illustration {
            max-width: 300px;
            margin: 2rem auto;
        }
        .error-code {
            font-size: 5rem;
            font-weight: bold;
            color: var(--bs-primary);
            line-height: 1;
        }
        .back-home {
            transition: all 0.3s;
        }
        .back-home:hover {
            transform: translateX(-5px);
        }
    </style>
</head>
<body>
    <div class="error-page">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="error-card">
                        <div class="card-body p-5 text-center">
                            <div class="error-illustration">
                                @yield('illustration')
                            </div>
                            <div class="error-code mb-4">@yield('code')</div>
                            <h1 class="h3 mb-4">@yield('title')</h1>
                            <p class="text-muted mb-4">@yield('message')</p>
                            <a href="{{ route('home') }}" class="btn btn-primary back-home">
                                <i class="bi bi-house-door me-2"></i>Back to Home
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
