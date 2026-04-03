<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <!-- SWEETALERT -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            margin: 0;
            font-family: sans-serif;
            display: flex;
        }

        .sidebar {
            width: 220px;
            background: #2f6fed;
            color: white;
            height: 100vh;
            padding: 20px;
            position: relative;
        }

        .sidebar h2 {
            font-size: 18px;
        }

        .menu {
            margin-top: 30px;
        }

        .menu a {
            display: block;
            padding: 10px;
            color: white;
            text-decoration: none;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .menu a:hover {
            background: rgba(255,255,255,0.2);
        }

        .content {
            flex: 1;
            padding: 30px;
            background: #f5f5f5;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
        }

        table {
            width: 100%;
            margin-top: 15px;
            border-collapse: collapse;
        }

        table th {
            background: #f0f4f8;
            font-weight: bold;
        }

        table th, table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        table tr:hover {
            background: #f1f5ff;
        }

        .status-aktif {
            color: green;
            font-weight: bold;
        }

        .status-nonaktif {
            color: red;
            font-weight: bold;
        }

        /* BOTTOM AREA */
        .bottom-area {
            position: absolute;
            bottom: 80px;
            left: 20px;
            right: 20px;
        }

        .profile {
            display: flex;
            align-items: center;
            background: rgba(255,255,255,0.15);
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .avatar {
            width: 40px;
            height: 40px;
            background: white;
            color: #2f6fed;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-right: 10px;
        }

        .info .name {
            font-size: 14px;
            font-weight: bold;
        }

        .info .role {
            font-size: 12px;
            opacity: 0.8;
        }

        .logout-btn {
            width: 100%;
            padding: 10px;
            border: none;
            background: #ff4d4f;
            color: white;
            border-radius: 5px;
            cursor: pointer;
        }

        .logout-btn:hover {
            background: #d9363e;
        }
    </style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h2>KUBE</h2>

    <div class="menu">
        <a href="#">Data User</a>
    </div>

    <!-- BOTTOM -->
    <div class="bottom-area">

        <!-- PROFILE -->
        <div class="profile">
            <div class="avatar">
                {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
            </div>
            <div class="info">
                <div class="name">{{ auth()->user()->nama }}</div>
                <div class="role">
                    {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
                </div>
            </div>
        </div>

        <!-- LOGOUT -->
        <form method="POST" action="/logout">
            @csrf
            <button class="logout-btn">Logout</button>
        </form>

    </div>
</div>

<!-- CONTENT -->
<div class="content">

    <h1>Data User</h1>
    <p>Kelola data user KUBE</p>

    <div class="card">
        <table>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No HP</th>
                <th>Alamat</th>
                <th>Role</th>
                <th>Status</th>
            </tr>

            @foreach($users as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->nama }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->no_hp }}</td>
                <td>{{ $user->alamat }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $user->role)) }}</td>
                <td class="{{ $user->status == 'aktif' ? 'status-aktif' : 'status-nonaktif' }}">
                    {{ ucfirst($user->status) }}
                </td>
            </tr>
            @endforeach

        </table>
    </div>

</div>

<!-- SWEET ALERT SUCCESS -->
@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        title: 'Login Berhasil 👋',
        text: "{{ session('success') }}",
        icon: 'success',
        confirmButtonColor: '#2f6fed',
        timer: 3000,
        timerProgressBar: true,
        showConfirmButton: false
    });
});
</script>
@endif

<!-- SWEET ALERT ERROR -->
@if(session('error'))
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        title: 'Gagal!',
        text: "{{ session('error') }}",
        icon: 'error',
        confirmButtonColor: '#d33'
    });
});
</script>
@endif

</body>
</html>