<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Khmer Angkor | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        /* Background inspired by Angkor temple colors */
        body {
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", Arial, sans-serif;
            height: 100vh;
            background: linear-gradient(135deg, #a47e3c 0%, #3c2a21 100%);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Container */
        .login-box {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            padding: 40px 30px;
            width: 100%;
            max-width: 380px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        /* Company name */
        .company {
            font-family: 'Georgia', serif;
            font-size: 28px;
            font-weight: bold;
            color: #a47e3c;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 14px;
            color: #6b5a4a;
            margin-bottom: 25px;
            font-style: italic;
        }

        /* Form title */
        .login-box h2 {
            color: #3c2a21;
            font-size: 22px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        /* Label */
        .login-box label {
            display: block;
            text-align: left;
            margin-bottom: 6px;
            font-weight: 600;
            color: #3c2a21;
        }

        /* Input fields */
        .login-box input[type="email"],
        .login-box input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #bba27e;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 14px;
            background-color: #fffaf3;
            color: #3c2a21;
            transition: all 0.3s ease;
        }

        /* Hover + Focus with Angkor gold highlight */
        .login-box input[type="email"]:hover,
        .login-box input[type="password"]:hover,
        .login-box input[type="email"]:focus,
        .login-box input[type="password"]:focus {
            border-color: #a47e3c;
            outline: none;
            box-shadow: 0 0 5px #a47e3c;
            background-color: #fff8ea;
        }

        /* Button */
        .login-box button {
            width: 100%;
            background: #a47e3c;
            color: white;
            border: none;
            padding: 10px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .login-box button:hover {
            background: #8c682e;
        }

        /* Link */
        .login-box a {
            display: inline-block;
            margin-top: 12px;
            font-size: 14px;
            color: #3c2a21;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .login-box a:hover {
            color: #a47e3c;
            text-decoration: underline;
        }

        /* Mobile-friendly */
        @media (max-width: 480px) {
            .login-box {
                padding: 30px 20px;
                margin: 10px;
            }

            .company {
                font-size: 22px;
            }
        }
    </style>
</head>

<body>
    <div class="login-box">
        <div class="company">Khmer Angkor</div>

        <h2>Sign In</h2>
        <form id="loginForm" method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit">Login</button>
            <div style="margin-top:10px;">
                <a href="#">Forgot password?</a>
            </div>

            <div id="errorMessage" style="color:red; margin-top:10px;">
                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
        </form>
    </div>
</body>

</html>
