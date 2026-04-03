<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: #f5f5f5;
}

.container {
    display: flex;
    height: 100vh;
    align-items: center;
    justify-content: center;
}

.card {
    display: flex;
    width: 900px;
    height: 500px; /* biar fix simetris */
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.left {
    width: 50%;
    background: #f0f4f8;
    padding: 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.left h2 {
    color: green;
    font-weight: 600;
    margin-bottom: 20px;
}

.left img {
    max-width: 100%;
}

/* RIGHT */
.right {
    width: 50%;
    padding: 40px;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.right img {
    width: 70px;
    margin: 0 auto 15px;
}

.right h3 {
    margin: 0;
    text-align: center;
}

.right p {
    text-align: center;
    margin-bottom: 20px;
    color: #666;
}

/* FORM */
.form-group {
    margin-bottom: 15px;
}

.form-group label {
    font-size: 14px;
    display: block;
    margin-bottom: 5px;
}

.form-group input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    box-sizing: border-box;
}

/* BUTTON */
button {
    width: 100%;
    padding: 12px;
    background: #2f6fed;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: 0.3s;
}

button:hover {
    background: #1e4fd8;
}

/* LINK */
.link {
    margin-top: 15px;
    text-align: center;
    font-size: 14px;
}

.link a {
    color: #2f6fed;
    text-decoration: none;
}

.link a:hover {
    text-decoration: underline;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .card {
        flex-direction: column;
        width: 95%;
        height: auto;
    }

    .left, .right {
        width: 100%;
    }
}
</style>
</head>
<body>

<div class="container">
    <div class="card">

        <!-- LEFT -->
        <div class="left">
            <h2>Sistem<br>Kelompok Usaha Bersama</h2>
        </div>

        <!-- RIGHT -->
        <div class="right">

            <h3>Selamat Datang</h3>
            <p>Silahkan masuk untuk melanjutkan</p>

            @if(session('error'))
                <p style="color:red">{{ session('error') }}</p>
            @endif

            <form method="POST" action="/login">
                @csrf

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Masukkan email" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Masukkan password" required>
                </div>

                <button type="submit">Masuk</button>
            </form>

        </div>

    </div>
</div>

</body>
</html>