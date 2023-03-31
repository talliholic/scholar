class Flow_chart {
    #create(e) {
        e.preventDefault()
        const body = new FormData(e.target)
        body.append("request", "create")
        fetch("/scholar/api/flow_chart.php", {method:"POST", body})
        .then((res)=> res.json())
        .then((res) => {
            if(!res.reason) {
                alert("I could not create your flow_chart. Try again.")
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
        const go_ahead = confirm("This will delete the flow chart and all its related sequences. The activities corresponding to this guide will also be removed. Students' scores won't be removed. Do you want to proceed?")

        if (go_ahead) {
            const body = new FormData()
            body.append("request", "soft_delete")
            body.append("id", e.target.id)
            fetch("/scholar/api/flow_chart.php", {method:"POST", body})
            .then((res)=> res.json())
            .then((res) => {
                if(!res.success) {
                    alert("Flow chart NOT deleted. Try again!")
                } else {
                    alert("Flow chart deleted")            
                    location.reload()
                }
            })
        }
    }

    delete_handler(buttons) {
        buttons.forEach((button) => button.addEventListener("click", this.#delete))    
    }

    //CONNECT TO DELETE DEFINITION API
    #delete_sequence(e) {
        e.preventDefault()
        const go_ahead = confirm("This will delete the sequence and remove it from the activities. Do you want to proceed?")

        if (go_ahead) {
            const body = new FormData()
            body.append("request", "delete_sequence")
            body.append("id", e.target.id)
            fetch("/scholar/api/flow_chart.php", {method:"POST", body})
            .then((res)=> res.json())
            .then((res) => {
                if(!res.success) {
                    alert("Sequence COULD NOT be deleted. Try again.")
                } else {
                    alert("Sequence DELETED.")
                    location.reload()
                }
            })
        }
    }

    delete_sequence_handler(buttons) {
        buttons.forEach((button) => button.addEventListener("click", this.#delete_sequence))         
    }

    #editToggleHidden(e){
        e.preventDefault()
        e.target.classList.toggle("hidden")
        document.getElementById("guide").classList.toggle("hidden")
        document.getElementById("cancel-edition").classList.toggle("hidden")
    }

    #sequence_exists(e, default_value, flow_chart_id) {
        e.preventDefault()
        if (default_value !== e.target.value.trim()) {
            const body = new FormData()
            body.append("request", "field_exists")
            body.append("column", "sequence")
            body.append("flow_chart_id", flow_chart_id)
            body.append("field", e.target.value)
            fetch("/scholar/api/flow_chart.php", {method:"POST", body})
            .then((res)=> res.json())
            .then((res) => {
                if(res.success) {
                    alert("That sequence is already used in this flow chart! Use another sequence.")
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
        form.definition.addEventListener("focusout", () => form.definition.value = form.definition.value.trim()) 
        form.addEventListener("submit", this.#update)
    }
}

export default Flow_chart