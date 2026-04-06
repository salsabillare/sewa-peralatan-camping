<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sewa Peralatan Camping</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #558B2F 0%, #689F38 50%, #558B2F 100%);
            background-attachment: fixed;
            position: relative;
            overflow-x: hidden;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset("storage/images/gunung.jpg") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            opacity: 1;
            pointer-events: none;
            z-index: 0;
        }
        
        .login-container {
            position: relative;
            z-index: 10;
        }
        
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-left: 5px solid #8BC34A;
        }
        
        .login-header {
            background: linear-gradient(135deg, #8BC34A 0%, #558B2F 100%);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            margin: -32px -32px 24px -32px;
            text-align: center;
        }
        
        .login-header h1 {
            font-size: 28px;
            font-weight: bold;
            margin: 0;
        }
        
        .camping-emoji {
            font-size: 32px;
            margin-right: 8px;
        }
        
        input[type="email"],
        input[type="password"] {
            border-color: #8BC34A !important;
            transition: all 0.3s ease;
        }
        
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #8BC34A !important;
            box-shadow: 0 0 0 3px rgba(139, 195, 74, 0.1) !important;
            outline: none !important;
        }
        
        button[type="submit"] {
            background: linear-gradient(135deg, #8BC34A 0%, #558B2F 100%);
            transition: all 0.3s ease;
        }
        
        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(139, 195, 74, 0.3);
        }
        
        .register-link {
            color: #8BC34A;
        }
        
        .register-link:hover {
            color: #558B2F;
            text-decoration: underline;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">

<div class="login-container w-full max-w-md px-4">
    <div class="login-card p-8 rounded-lg shadow-2xl">
        <div class="login-header">
            <div>
                <span class="camping-emoji">⛺</span>
                <h1>CampGear Hub</h1>
                <p class="text-green-100 text-sm mt-1">Sewa Peralatan Camping</p>
            </div>
        </div>

        @if(session('error'))
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded border border-red-300">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-semibold mb-2">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="w-full p-3 border-2 border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-400">
                @error('email')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-6">
                <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
                <input id="password" type="password" name="password" required
                    class="w-full p-3 border-2 border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-400">
                @error('password')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white py-3 rounded font-semibold text-lg">
                🔓 Masuk
            </button>
        </form>

        <p class="mt-6 text-center text-gray-700">
            Belum punya akun? 
            <a href="{{ route('register') }}" class="register-link font-semibold">Daftar sekarang</a>
        </p>
    </div>
</div>

</body>
</html>
