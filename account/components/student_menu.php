<h1 class="center-nomargin-title">Scholar</h1>
<nav class="row">
    <a href="/scholar/user">Account</a>
    <a href="/scholar/activities">Activities</a>
    <a href="/scholar/stats">Stats</a>
    <a href="/scholar/tutorials/?page=students">Tutorials</a>
    <a id="logout-btn" href="">Log out</a>
</nav>

<script type="module">
    import User from "/scholar/scripts/classes/User.js"
    const user = new User()
    user.logoutHandler(document.querySelector("#logout-btn"))
</script>