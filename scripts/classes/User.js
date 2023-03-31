class User {
    #fieldExists(e, column) {
        const body = new FormData
        body.append("column", column)
        body.append("field", e.target.value)
        body.append("request", "field-exists")
        fetch("/scholar/api/user.php", {method:"POST", body})
        .then((res)=> res.json())
        .then(({success})=> {
            if(success){
               alert(`Please use a different ${column}.`) 
               e.target.value = ""
               e.target.focus()
            }
        })
    }

    #login(e) {
        e.preventDefault()
        const body = new FormData(e.target)
        body.append("request", "login")
        fetch("/scholar/api/user.php", {method:"POST", body})
        .then((res)=> res.json())
        .then(({success}) => {
            if(!success) {
                alert("Check your login details and try again!")
            } else {
                alert("You have logged in. Welcome!")
                window.location.replace("/scholar/activities")
            }
        })
    }

    loginHandler(form) {
        form.email.addEventListener("focusout", (e) => form.email.value = form.email.value.trim())
        form.addEventListener("submit", this.#login)
    }

    #logout(e) {
        e.preventDefault()
        const body = new FormData()
        body.append("request", "logout")
        fetch("/scholar/api/user.php", {method:"POST", body})
        .then((res)=> res.json())
        .then(({success}) => {
            if(success) {
                alert("You have logged out of your account!")
                window.location.replace("/scholar/account")
            } else {
                alert("I COULD NOT log you out. Try again!")
            }
        })
    }

    logoutHandler(btn) {
        btn.addEventListener("click", this.#logout)
    }

    #matchInputs(input1, input2, fields) {
        if(input2.value) {
            if(input1.value !== input2.value) {
                alert(`${fields} DO NOT match!`)
                input1.value = ""
                input1.focus()
            }
        }
     } 

    #signup(e) {
        e.preventDefault()
        const body = new FormData(e.target)
        body.append("request", "create")
        fetch("/scholar/api/user.php", {method:"POST", body})
        .then((res)=> res.json())
        .then(({success}) => {
            if(!success) {
                alert("I could not sign you up. Try again.")
            } else {
                alert("I have SUCCESSFULLY signed you up. Log in now!")
                window.location.replace("/scholar/auth")
            }
        })
    }

    signupHandler(form) {
        form.name.addEventListener("focusout", () => form.name.value = form.name.value.trim())
        form.username.addEventListener("change", (e) => {
            form.username.value = form.username.value.trim()
            this.#fieldExists(e, "username")
        })
        form.email.addEventListener("focusout", () => form.email.value = form.email.value.trim())
        form.email.addEventListener("change", (e) => {            
            this.#matchInputs(form.email, form.email2, "Emails")
            this.#fieldExists(e, "email")
        })
        form.email2.addEventListener("focusout", () => form.email2.value = form.email.value.trim())
        form.email2.addEventListener("change", () => this.#matchInputs(form.email2, form.email, "Emails"))
        form.password.addEventListener("change", () => this.#matchInputs(form.password, form.password2, "Passwords"))
        form.password2.addEventListener("change", () => this.#matchInputs(form.password2, form.password, "Passwords"))
        form.addEventListener("submit", this.#signup)
    }
}

export default User