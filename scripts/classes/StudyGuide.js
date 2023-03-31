class StudyGuide {
    #create(e) {
        e.preventDefault()
        const body = new FormData(e.target)
        body.append("request", "create")
        fetch("/scholar/api/study_guide.php", {method:"POST", body})
        .then((res)=> res.json())
        .then((res) => {
            if(!res.reason) {
                alert("I could not create your study guide. Try again.")
            } else if(res.reason === "Success") {
                alert(res.msg)
                window.location.replace(`?page=edit&id=${res.id}`)
            } else if(res.reason === "Not verified") {
                alert(res.msg)
            }
        })
    }

    createHandler(form){
        form.title.addEventListener("focusout", () => form.title.value = form.title.value.trim()) 
        form.addEventListener("submit", this.#create)
    }
    #delete(e) {
        e.preventDefault()
        const go_ahead = confirm("This will delete the study guide and all its related definitions. The activities corresponding to this guide will also be removed. Students' scores won't be removed. Do you want to proceed?")

        if (go_ahead) {
            const body = new FormData()
            body.append("request", "soft_delete")
            body.append("id", e.target.id)
            fetch("/scholar/api/study_guide.php", {method:"POST", body})
            .then((res)=> res.json())
            .then((res) => {
                if(!res.success) {
                    alert("Study guide NOT deleted. Try again!")
                } else {
                    alert("Study guide deleted")            
                    location.reload()
                }
            })
        }
    }

    delete_handler(buttons) {
        buttons.forEach((button) => button.addEventListener("click", this.#delete))    
    }

    //CONNECT TO DELETE DEFINITION API
    #delete_definition(e) {
        e.preventDefault()
        const go_ahead = confirm("This will delete the definition and remove it from the activities. Do you want to proceed?")

        if (go_ahead) {
            const body = new FormData()
            body.append("request", "delete_definition")
            body.append("id", e.target.id)
            fetch("/scholar/api/study_guide.php", {method:"POST", body})
            .then((res)=> res.json())
            .then((res) => {
                if(!res.success) {
                    alert("Definition COULD NOT be deleted. Try again.")
                } else {
                    alert("Definition DELETED.")
                    location.reload()
                }
            })
        }
    }

    delete_definition_handler(buttons) {
        buttons.forEach((button) => button.addEventListener("click", this.#delete_definition))         
    }

    #editToggleHidden(e){
        e.preventDefault()
        e.target.classList.toggle("hidden")
        document.getElementById("guide").classList.toggle("hidden")
        document.getElementById("cancel-edition").classList.toggle("hidden")
    }

    #word_exists(e, default_value, guide_id) {
        e.preventDefault()
        if (default_value !== e.target.value.trim()) {
            const body = new FormData()
            body.append("request", "field_exists")
            body.append("column", "word")
            body.append("guide_id", guide_id)
            body.append("field", e.target.value)
            fetch("/scholar/api/study_guide.php", {method:"POST", body})
            .then((res)=> res.json())
            .then((res) => {
                if(res.success) {
                    alert("That word is already used in this guide! Use another word.")
                    e.target.value = default_value
                    e.target.focus()   
                }       
            })
        }
    }

    #cancelEditToggleHidden(e){
        e.preventDefault()
        e.target.classList.toggle("hidden")
        document.getElementById("guide").classList.toggle("hidden")
        document.getElementById("edit").classList.toggle("hidden")
    }
 
    #update(e) {
        e.preventDefault()
        if ((e.target.word.value !== "" && e.target.definition.value === "") || (e.target.definition.value !== "" && e.target.word.value === "") ) {
            alert("If you are adding a definition, do not forget to type a word and its definition.")
            return;
        }
        const body = new FormData(e.target)
        body.append("request", "update")
        fetch("/scholar/api/study_guide.php", {method:"POST", body})
        .then((res)=> res.json())
        .then((res) => {
            if(!res.success) {
                alert(res.msg)
            } else {
                alert(res.msg)
                window.location.replace(`?page=definitions&id=${res.id}`)
            }
        })
    }

    #update_definition(e) {
        e.preventDefault()
        const body = new FormData(e.target)
        body.append("request", "update_definition")
        fetch("/scholar/api/study_guide.php", {method:"POST", body})
        .then((res)=> res.json())
        .then((res) => {
            if(!res.success) 
                alert(res.msg)
             else {
                alert(res.msg)
                window.location.replace(`?page=definitions&id=${res.id}`)
            }
        })
    }

    update_definition_handler(form) {
        const word_input = form.word.value
        const definition_input = form.definition.value
        form.word.addEventListener("focusout", () => {
            if (!form.word.value) form.word.value = word_input
            form.word.value = form.word.value.trim()
        })
        form.word.addEventListener("change", (e) => this.#word_exists(e, word_input, form.id.value))
        form.definition.addEventListener("focusout", () => {
            if (!form.definition.value) form.definition.value = definition_input
            form.definition.value = form.definition.value.trim()
        })
        form.addEventListener("submit", this.#update_definition)
    }

    updateHandler(form){
        form.querySelector("#edit").addEventListener("click", this.#editToggleHidden)
        form.querySelector("#cancel-edition").addEventListener("click", this.#cancelEditToggleHidden)
        form.title.addEventListener("focusout", () => form.title.value = form.title.value.trim())
        form.word.addEventListener("focusout", () => form.word.value = form.word.value.trim())  
        form.definition.addEventListener("focusout", () => form.definition.value = form.definition.value.trim()) 
        form.addEventListener("submit", this.#update)
    }
}

export default StudyGuide