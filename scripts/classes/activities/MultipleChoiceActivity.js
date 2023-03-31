class MultipleChoiceActivity {
    //STATE MAY CHANGE EVENT AFTER EVENT
    #form = document.querySelector("form")
    #answers = []
    #inputs = document.querySelectorAll(".input")
    #time = 0

    constructor() {
        setInterval(() => this.#time++, 1000)
        //CREATES AN ANSWERS' PROPERTY OR STATE
        document.querySelectorAll(".word").forEach((answer, i) => {
            this.#answers.push({
                input: "",
                target : answer.id,
                score: 0,
            })
        });
        this.#inputs.forEach((input) => input.addEventListener("change", this.#map_answers.bind(this)))    
    }

    #getPDF(e) {
        e.preventDefault()
        if (document.getElementById("best_results")) document.getElementById("best_results").classList.toggle("hidden")
        document.querySelector("header").classList.toggle("hidden") 
        document.querySelector(".buttons").classList.toggle("hidden")
        document.querySelectorAll(".speak").forEach((speak)=>speak.classList.toggle("hidden"))
        document.querySelector(".worksheet-heading").classList.toggle("hidden")
        document.querySelectorAll(".medium-img").forEach((item) => item.setAttribute("style", "height:100px"))
        document.querySelector(".items").setAttribute("style", "display:flex;flex-flow:row wrap;column-gap:40px")         
        window.print()
        document.querySelector("header").classList.toggle("hidden") 
        document.querySelector(".buttons").classList.toggle("hidden")
        document.querySelectorAll(".speak").forEach((speak)=>speak.classList.toggle("hidden"))
        document.querySelector(".worksheet-heading").classList.toggle("hidden")  
        document.querySelectorAll(".medium-img").forEach((item) => item.removeAttribute("style"))
        document.querySelector(".items").removeAttribute("style")     
        if (document.getElementById("best_results")) document.getElementById("best_results").classList.toggle("hidden")         
    }

    getPDFHandler() { 
        document.querySelector("#print_worksheet").addEventListener("click", this.#getPDF)
    }
    
    #get_best_results() {
        if (this.#form.guide_id) {
            const body = new FormData()
            body.append("request", "get_scores_by_activity")
            body.append("guide_id", this.#form.guide_id.value)
            body.append("activity_name", this.#form.activity.value)
            fetch("/scholar/api/activity.php", {method:"POST", body})
            .then((res)=> res.json())
            .then((res) => {
                const fieldset = document.getElementById("best_results")
                const legend = document.createElement("legend")
                if(!res.success) {
                    fieldset.appendChild(legend)
                    legend.innerText = "No results yet"
                } else {
                    legend.innerText = "Best Results"
                    const table = document.createElement("table")
                    const tr = document.createElement("tr")
                    tr.innerHTML = "<th>Taken</th><th>Score</th><th>Duration</th>"
                    fieldset.appendChild(legend)
                    fieldset.appendChild(table)
                    table.style = "margin:auto"
                    table.appendChild(tr)
                    res.scores.forEach((score) => {
                        const tr = document.createElement("tr")
                        const td1 = document.createElement("td")
                        const td2 = document.createElement("td")
                        const td3 = document.createElement("td")
                        table.appendChild(tr)
                        tr.appendChild(td1)
                        tr.appendChild(td2)
                        tr.appendChild(td3)
                        td1.innerText = score.date_taken
                        td2.innerText = score.score
                        td1.style = "border:1px dotted black;padding:0 2px"
                        td2.style = "text-align:right;border:1px dotted black;padding-right:2px"
                        td3.style = "text-align:center;border:1px dotted black;padding-right:2px"
                        td3.innerText = score.duration
                    })
                }
             })
        } 
    }

    //GETS FINAL STATE AND TOTAL SCORE
    #get_multiple_answers(e) {
        document.getElementById("best_results").innerHTML = ""
        document.getElementById("play-again-button").removeAttribute("disabled")
        document.getElementById("submit-button").setAttribute("disabled", "")
        e.preventDefault()
        const checks = document.querySelectorAll(".check")
        const crosses = document.querySelectorAll(".cross")
        const score_array = []
        this.#answers.forEach((answer) => score_array.push(answer.score))
        //SHOWS CHECKS AND CROSSES ACCORDING TO SCORE
        score_array.forEach((score, i) => {
            if (score === 1) checks[i].classList.remove("hidden")
            if (score === 0) crosses[i].classList.remove("hidden")            
        })
        const total_score = score_array.reduce((partialSum, a) => partialSum + a, 0);
        const grade = Math.round((total_score / score_array.length) * 100)
        this.#save_result(e, grade, () => this.#get_best_results())
        this.#form.reset()
        this.#inputs.forEach((input) => input.classList.add("none"))
        document.querySelectorAll(".chosen").forEach((chosen) => chosen.classList.remove("hidden"))
        window.scrollTo(0, 0);
        // console.log(this.#answers, total_score)        
    }

    getMultipleAnswers() {
        this.#get_best_results()
        this.#form.addEventListener("submit", this.#get_multiple_answers.bind(this))
        document.getElementById("play-again-button").addEventListener("click", this.#play_again)
        document.querySelectorAll(".speak").forEach((speak)=>speak.addEventListener("click", this.#read_out_loud_handler.bind(this)))
    }

    #read_out_loud(text) {
        // 'speechSynthesis' in window ? console.log("Web Speech API supported!") : console.log("Web Speech API not supported :-(")
    
        const synth = window.speechSynthesis
        const utterThis = new SpeechSynthesisUtterance(text)
        utterThis.pitch = 1
        utterThis.rate = 0.8
        utterThis.lang = 'en-US'
    
        synth.speak(utterThis)
    }
    #read_out_loud_handler(e) {
        const text = e.target.previousElementSibling.textContent
        this.#read_out_loud(text)
    }

    //MODIFIES STATE WHEN INPUT CHANGES
    #map_answers() {
        this.#inputs.forEach((input) => {
            if (input.checked) {
                input.previousElementSibling.classList.add("chosen")
            } else {
                if (input.previousElementSibling.classList.contains("chosen"))
                input.previousElementSibling.classList.remove("chosen")
            }
        })
        this.#answers.forEach((answer, i) => {
            let score = 0;
            const input = this.#form[`question_${i}`].value  
            if (input === answer.target) score = 1
            this.#answers[i] = {
                ...answer,
                input,
                score              
            }
        })
    }

    #play_again(e) {
        e.preventDefault()
        location.reload()
        window.scrollTo(0, 0);
    }
    #save_result(e, score, callback = "") {
        const body = new FormData(e.target)
        body.append("request", "save-result")
        body.append("score", score)
        body.append("duration", this.#time)
        fetch("/scholar/api/activity.php", {method:"POST", body})
        .then((res)=> res.json())
        .then((res) => {
            if(!res.success) {
                alert(`You scored ${score} points in ${this.#time} seconds. ${res.msg}`)
            } else {
                alert(`You scored ${score} points in ${this.#time} seconds. ${res.msg}`)
                callback()
            }
        })
    }
}
export default MultipleChoiceActivity