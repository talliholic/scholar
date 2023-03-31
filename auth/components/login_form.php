<form class="center-child">
    <fieldset class="column mid-width mediumblue">
        <legend>Log in</legend>
        <input type="text" name="email" placeholder="Username or Email if you are a teacher">
        <input type="password" name="password" placeholder="Password">
        <input type="submit" value="Submit">
        <a class="lightblue" href="/scholar/auth/signup.php">Or sign up as a teacher</a>
    </fieldset>
</form>
<script type="module">
    import User from "/scholar/scripts/classes/User.js"
    const user = new User()
    user.loginHandler(document.querySelector("form"))
</script>