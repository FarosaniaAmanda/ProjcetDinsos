<!DOCTYPE html>

<html>

<head>

    <title>
        Login Sistem Pendataan
    </title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Segoe UI';
        }

        body{

            background:#f5f8ff;

            height:100vh;

            display:flex;

            justify-content:center;

            align-items:center;

        }

        /* BOX LOGIN */

        .login{

            width:450px;

            background:white;

            padding:40px;

            border-radius:20px;

            box-shadow:0 10px 30px #ddd;

        }

        /* LOGO */

        .logo{

            text-align:center;

            font-size:55px;

            color:#252A86;

        }

        h1{

            text-align:center;

            color:#252A86;

            margin-top:20px;

        }

        h3{

            text-align:center;

            margin:15px 0 35px;

        }

        /* FORM */

        label{

            display:block;

            font-weight:bold;

            margin-top:15px;

        }

        input,
        select{

            width:100%;

            padding:14px;

            margin-top:8px;

            border:1px solid #ddd;

            border-radius:8px;

            font-size:15px;

        }

        /* DROPDOWN ROLE */

        select{

            background:white;

            cursor:pointer;

        }

        /* LUPA PASSWORD */

        .forgot{

            text-align:right;

            margin-top:15px;

        }

        .forgot a{

            color:#252A86;

            text-decoration:none;

            font-size:14px;

        }

        .forgot a:hover{

            text-decoration:underline;

        }

        /* BUTTON */

        .login-btn{

            width:100%;

            margin-top:25px;

            background:#252A86;

            color:white;

            padding:15px;

            border:none;

            border-radius:8px;

            font-size:16px;

            cursor:pointer;

        }

        .login-btn:hover{

            background:#394bb8;

        }

    </style>

</head>

<body>

    <div class="login">

        <div class="logo">

            🏛️

        </div>

        <h1>

            Sistem Pendataan Sensus

        </h1>

        <h3>

            Login Sistem

        </h3>

        <label>

            Username

        </label>


        <input 
            type="text" 
            placeholder="Masukkan username">

        <label>

            Password

        </label>

        <input 
            type="password"
            placeholder="Masukkan password">

        <label>

            Login sebagai

        </label>

        <select>

            <option>

                Admin

            </option>

            <option>

                Petugas

            </option>

            <option>

                Verifikator

            </option>

        </select>

        <div class="forgot">

            <a href="#">

                Lupa Password?

            </a>

        </div>

        <button class="login-btn">

            Masuk →

        </button>

    </div>

</body>

</html>