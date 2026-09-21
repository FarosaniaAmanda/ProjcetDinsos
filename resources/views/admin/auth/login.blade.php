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
    width:380px;           /* sebelumnya 450px */
    background:white;
    padding:28px;          /* sebelumnya 40px */
    border-radius:18px;
    box-shadow:0 10px 30px #ddd;
}

/* LOGO */
.logo{
    text-align:center;
    font-size:42px;        /* sebelumnya 55px */
    color:#252A86;
}

h1{
    text-align:center;
    color:#252A86;
    margin-top:12px;
    font-size:24px;
}

h3{
    text-align:center;
    margin:10px 0 24px;
    font-size:16px;
}

/* FORM */
label{
    display:block;
    font-weight:bold;
    margin-top:12px;
}

input,
select{
    width:100%;
    padding:12px;          /* sebelumnya 14px */
    margin-top:8px;
    border:1px solid #ddd;
    border-radius:8px;
    font-size:14px;
}

/* DROPDOWN ROLE */
select{
    background:white;
    cursor:pointer;
}

/* LUPA PASSWORD */
.forgot{
    text-align:right;
    margin-top:12px;
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
    margin-top:20px;
    background:#252A86;
    color:white;
    padding:13px;          /* sebelumnya 15px */
    border:none;
    border-radius:8px;
    font-size:15px;
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