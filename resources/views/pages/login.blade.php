<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary: #76B4FF;
            --primary-hover: #4e9eff;
        }

        body {
            min-height: 100vh;
            background: #f5f7fb;
        }

        .login-wrapper {
            min-height: 100vh;
        }

        .login-left {
            background: linear-gradient(135deg, #1f2937, #111827);
            color: #fff;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .login-right {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .login-card {
            width: 100%;
            max-width: 450px;
            border: none;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
        }

        .company-logo {
            max-height: 200px;
            margin-bottom: 20px;
        }

        .feature-item {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: rgba(255, 255, 255, .1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .btn-login {
            background: var(--primary);
            border-color: var(--primary);
            color: #000;
            font-weight: 600;
        }

        .btn-login:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
            color: #000;
        }

        @media(max-width:992px) {
            .login-left {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="row g-0 min-vh-100">
        <!-- LEFT SIDE -->
        <div class="col-lg-7 login-left">
            <img src="{{ asset('assets/img/giken.png') }}" alt="Giken" class="company-logo">
            <h1 class="fw-bold mb-3"> Inventory Management System </h1>
            <p class="text-light opacity-75 mb-5"> Integrated warehouse, forecasting, inventory control, delivery
                management and reporting platform. </p>
            <div class="feature-item">
                <div class="feature-icon"> <i class="bi bi-box-seam"></i> </div>
                <div>
                    <h6 class="mb-1">Inventory Control</h6> <small class="text-light opacity-75"> Real-time stock
                        monitoring and management. </small>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"> <i class="bi bi-graph-up-arrow"></i> </div>
                <div>
                    <h6 class="mb-1">Demand Forecasting</h6> <small class="text-light opacity-75"> Predict future demand
                        using historical transactions. </small>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon"> <i class="bi bi-truck"></i> </div>
                <div>
                    <h6 class="mb-1">Warehouse Delivery</h6> <small class="text-light opacity-75"> Manage delivery in
                        and delivery out efficiently. </small>
                </div>
            </div>
        </div> <!-- RIGHT SIDE -->
        <div class="col-lg-5 login-right">
            <div class="card login-card">
                <div class="card-body p-5">
                    <div class="text-center mb-4"> <img src="{{ asset('assets/img/giken.png') }}" height="50"
                            alt="Logo">
                        <h3 class="mt-3 fw-bold"> Sign In </h3>
                        <p class="text-muted mb-0"> Login to your account </p>
                    </div>
                    <form method="POST" action="{{ route('login.authenticate') }}"> @csrf
                        <div class="mb-3">
                            <label class="form-label"> Username </label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-person"></i>
                                </span>
                                <input type="text" name="username" value="{{ old('username') }}"
                                    class="form-control @error('username') is-invalid @enderror"
                                    placeholder="Enter your username">
                                @error('username')
                                <div class="invalid-feedback">
                                    {{$message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-4"> <label class="form-label"> Password </label>
                            <div class="input-group"> <span class="input-group-text"> <i class="bi bi-lock"></i> </span>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Enter your password"> @error('password') <div class="invalid-feedback">
                                    {{ $message }} </div> @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-login w-100 py-2">
                            Login
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
