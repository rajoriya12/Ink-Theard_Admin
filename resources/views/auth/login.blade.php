<!DOCTYPE html>
<html>
<head>

    <title>Admin Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body
style="
background:#0a0a0a;
display:flex;
justify-content:center;
align-items:center;
height:100vh;
"
>

<div
class="card p-4"
style="
width:400px;
background:#151515;
color:white;
"
>

<h2 class="mb-4 text-center">
Admin Login
</h2>

@if(session('error'))

<div class="alert alert-danger">

    {{ session('error') }}

</div>

@endif

<form method="POST" action="/login">

    @csrf

    <div class="mb-3">

        <label>Email</label>

        <input
            type="email"
            name="email"
            class="form-control"
        >

    </div>

    <div class="mb-3">

        <label>Password</label>

        <input
            type="password"
            name="password"
            class="form-control"
        >

    </div>

    <button
        class="btn btn-warning w-100"
    >
        Login
    </button>

</form>

</div>

</body>
</html>