<!-- navbar.php or inside your view file -->
<style>
    .navbar {
        background-color: rgba(0, 0, 0, 0.85);
        padding: 15px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .navbar .logo {
        font-size: 1.5em;
        font-weight: bold;
        color: #fdd835;
        text-decoration: none;
    }

    .navbar .nav-links a {
        color: #fdd835;
        text-decoration: none;
        margin-left: 20px;
        font-weight: bold;
        transition: color 0.3s ease;
    }

    .navbar .nav-links a:hover {
        color: #fff;
    }
</style>

<div class="navbar">
    <a class="logo" href="/controllers/welcome.php">🏐 Volleyball Academy</a>
    <div class="nav-links">
        <a href="/controllers/welcome.php">Home</a>
        <a href="/controllers/courses.php">Courses</a>
        <a href="/controllers/about.php">About</a>
        <a href="/controllers/auth/login.php">Login</a>
    </div>
</div>
