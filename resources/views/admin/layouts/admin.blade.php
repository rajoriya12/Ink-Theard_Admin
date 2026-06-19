<!DOCTYPE html>
<html>

<head>

    <title>Ink & Thread Admin</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #0a0a0a;
            color: white;
            margin: 0;
        }

        .sidebar {
            width: 260px;
            height: 100vh;
            background: #111;
            position: fixed;
            left: 0;
            top: 0;
            padding: 25px;
        }

        .sidebar h2 {
            color: #B08D57;
            margin-bottom: 40px;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .sidebar a:hover {
            background: #B08D57;
            color: black;
        }

        .main {
            margin-left: 260px;
            padding: 30px;
        }

        .topbar {
            background: #151515;
            padding: 15px 25px;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .card-custom {
            background: #151515;
            border: none;
            border-radius: 15px;
            color: white;
        }
    </style>

</head>

<body>

    <div class="sidebar">

        <h2>Ink & Thread</h2>

        <a href="/">📊 Dashboard</a>
        <a href="/products">📦 Products</a>
        <a href="/customers">👥 Customers</a>
        <a href="/messages">📩 Messages</a>
        <a href="/orders">📦 Orders</a>
        <a href="/discounts">🏷 Discounts</a>
        <a href="/settings">⚙ Settings</a>
        <a href="/logout">🚪 Logout</a>

    </div>

    <div class="main">

        <div class="topbar">
            <h4>Admin Panel</h4>
        </div>

        @yield('content')

    </div>


    <script>
        document.getElementById('selectAll')
            .addEventListener('change', function() {

                document
                    .querySelectorAll('.product-checkbox')
                    .forEach(cb => {

                        cb.checked = this.checked;

                    });

            });
    </script>
</body>

</html>