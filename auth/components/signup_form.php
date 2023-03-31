<form class="center-child">
    <fieldset class="column mid-width mediumblue">
        <legend>Sign up</legend>
        <input type="text" name="name" minlength="2" maxlength="15" placeholder="Name" pattern="^[a-zA-Z ñÑáéíóú]+$" required>
        <input type="text" name="username" minlength="2" maxlength="15" placeholder="Username" pattern="^[a-zA-Z0-9 ñÑ]+$" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="email" name="email2" placeholder="Confirm your email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="password" name="password2" placeholder="Confirm your password" required>
        <input type="submit" name="signup" value="Submit" required>
        <a class="lightblue" href="/scholar/auth">Log in instead</a>
    </fieldset>
</form>
<script type="module">
    import User from "/scholar/scripts/classes/User.js"
    const user = new User()
    user.signupHandler(document.querySelector("form"))
</script>