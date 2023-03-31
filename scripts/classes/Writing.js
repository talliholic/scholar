class Writing {
    #words

    constructor(definitions) {
        this.#words = definitions.map((definition) => definition.word)
    }

    #focusout_handler(e) {
        e.target.value = e.target.value.trim()
        this.#validate()
    }

    #save(e) {
        e.preventDefault()
        const validated = this.#validate()
        if (validated) {
            const body = new FormData(e.target)
            body.append("request", "create")
            fetch("/scholar/api/writing.php", {method:"POST", body})
            .then((res)=> res.json())
            .then((res) => {
                if (res.success) {
                    alert("Your paragraph was saved!")
                    document.location.replace(`?page=by_activity&id=${res.id}`)
                } else {
                    alert("Your paragraph was not saved. Try again!")
                } 
            })
        }
    }

    save_handler() {
        document.getElementById("paragraph").addEventListener("focusout", this.#focusout_handler.bind(this))
        document.querySelector("form").addEventListener("submit", this.#save.bind(this))
    }

    #update(e) {
        e.preventDefault()
        const validated = this.#validate()
        if (validated) {
            const body = new FormData(e.target)
            body.append("request", "update")
            fetch("/scholar/api/writing.php", {method:"POST", body})
            .then((res)=> res.json())
            .then((res) => {
                if (res.success) {
                    alert("Your paragraph was saved!")
                    window.location.replace(`?page=by_activity&id=${res.id}`)
                } else {
                    alert("Your paragraph was not saved. Try again!")
                } 
            })
        }
    }

    update_handler() {
        document.getElementById("paragraph").addEventListener("focusout", this.#focusout_handler.bind(this))
        document.querySelector("form").addEventListener("submit", this.#update.bind(this))
    }

    #validate() {
        const min_words = this.#words.length * 4
        function onlyLetters(str) {
            return /^[A-Za-zñÑáéíóúÁÉÍÓÚ0-9;,.:-]*$/.test(str);
        }
        let chars = document.getElementById("paragraph").value.split(" ")
        chars = chars.filter((char) => char !== "")
        chars = chars.filter((char) => onlyLetters(char))
        const num_chars = chars.length
        if (num_chars < min_words) {
            alert(`You must write at least ${min_words} words. You wrote ${num_chars}.`)
            return false
        }
        return true
    }
}

export default Writing