class Classroom{
    #create(e) {
        e.preventDefault()
        const body = new FormData(e.target)
        body.append("request", "create")
        fetch("/scholar/api/classroom.php", {method:"POST", body})
        .then((res)=> res.json())
        .then((res) => {
            if(!res.success) {
                alert("I could not create your classroom. Try again.")
            } else {
                alert("Please add students to the classroom you JUST created.")
                window.location.replace(`?page=edit-classroom&id=${res.id}`)
            }
        })
    }

    createHandler(form){
        form.name.addEventListener("focusout", () => form.name.value = form.name.value.trim()) 
        form.addEventListener("submit", this.#create)
    }

    #createStudents(e) {
        e.preventDefault()
        const body = new FormData(e.target)
        body.append("request", "update")
        fetch("/scholar/api/classroom.php", {method:"POST", body})
        .then((res)=> res.json())
        .then((res) => {
            if(!res.success) {
                alert("Your data COULD NOT be saved. Try again.")
            } else {
                alert("Your data was saved.")
                 window.location.replace(`?page=students&id=${res.id}`)
            }
        })
    }

    createStudentsHandler(form) {
        form.addEventListener("submit", this.#createStudents)
        form.students.addEventListener("focusout", this.#sanitizeCreateStudentsForm)
    }

    #delete_student(e) {
        e.preventDefault()
        const petition = confirm("This will DELETE the student and all his/her records on this website. Do you want to proceed?")
        if(petition) {
            const body = new FormData()
            body.append("request", "delete_student")
            body.append("id", e.target.id)
            fetch("/scholar/api/classroom.php", {method:"POST", body})
            .then((res)=> res.json())
            .then((res) => {
                if(!res.success) {
                    alert("The student COULD NOT be deleted. Try again.")
                } else {
                    alert("The student and his/her records were deleted.")
                     location.reload()
                }
            })
        }
    }

    delete_student_handler(buttons) {
        buttons.forEach((button) => button.addEventListener("click", this.#delete_student))  
    }

    deleteHandler() {
        document.querySelectorAll(".delete-button").forEach((btn) => btn.addEventListener("click", this.#delete))
    }

    #delete(e) {
        e.preventDefault()
        const permission = confirm("This will delete all students in this classroom. It will delete all their activities and results.")

        if(permission) {
            const body = new FormData()
            body.append("request", "delete")
            body.append("id", e.target.id)
            fetch("/scholar/api/classroom.php", {method:"POST", body})
            .then((res)=> res.json())
            .then((res) => {
                if(!res.success) alert(res.msg)
                 else {
                    alert(res.msg)
                    location.reload()
                }
            })
        }
    }
    
    #student_field_exists(e, column, default_value) {
        const body = new FormData
        body.append("column", column)
        body.append("field", e.target.value)
        body.append("request", "field-exists")
        fetch("/scholar/api/user.php", {method:"POST", body})
        .then((res)=> res.json())
        .then(({success})=> {
            if(success){
               alert(`Please use a different ${column}.`) 
               e.target.value = default_value
               e.target.focus()
            }
        })
    }

    #sanitizeCreateStudentsForm(e) {
        function onlyLettersAndSpaces(str) {
            return /^[A-Za-zñÑáéíóúÁÉÍÓÚ\s]*$/.test(str);
          }
        if(!onlyLettersAndSpaces(e.target.value)) {
            alert("Include ONLY names with letters. Leave a space to separate students. DO NOT use commas or any separator besides a space.")
            e.target.value = ""
            e.target.focus()
        }
    }

    #sanitizeUpdateStudent(e, default_value) {
        function onlyLettersAndSpaces(str) {
            return /^[A-Za-zñáéíóúÁÉÍÓÚ\s]*$/.test(str);
          }
        if(!onlyLettersAndSpaces(e.target.value)) {
            alert("You entered an invalid character.")
            e.target.value = default_value
            e.target.focus()
        }
    }

    #update_student(e) {
        e.preventDefault()
        const body = new FormData(e.target)
        body.append("request", "update_student")
        fetch("/scholar/api/classroom.php", {method:"POST", body})
        .then((res)=> res.json())
        .then((res) => {
            if(!res.success) alert("Student NOT updated. Try modifying the fields or back to classroom.")
             else {
                alert(res.msg)
                window.location.replace(`?page=students&id=${res.id}`)
            }
        })
    }

    update_student_handler(form) {
        const name_default_value = form.name.value
        const username_default_value = form.username.value

        form.addEventListener("submit", this.#update_student)
        form.name.addEventListener("focusout", (e) => {
            this.#sanitizeUpdateStudent(e, name_default_value)
            form.name.value = form.name.value.trim()
        })
        form.username.addEventListener("focusout", () => form.username.value = form.username.value.trim())       
        form.username.addEventListener("change", (e) => this.#student_field_exists(e, "username", username_default_value))       
    }

}
export default Classroom