const main_css_link = document.querySelectorAll("link")[0]
const unnecessary_css_link = document.querySelectorAll("link")[1]
let duration = 0;
setInterval(()=> duration++ ,1000)

//Place css link
main_css_link.insertAdjacentHTML("beforebegin", `
<link rel="stylesheet" href="./components/match_images.css">
` )

//Remove unnecessary link
unnecessary_css_link.remove()

const fill_container = document.querySelector("#fill_container")
const empty_container = document.querySelector(".empty_container")
const fills = document.querySelectorAll(".fill")
const empties = document.querySelectorAll(".empty")
const done = document.getElementById("done")
const try_again_btn = document.getElementById("try_again")
const form = document.querySelector("form")
const feedback = document.querySelector("#feedback")
const check_feedback = document.querySelectorAll(".check_feedback")
const first_set = document.querySelector("#first_set")

try_again_btn.addEventListener("click", try_again)

fills.forEach(fill=>fill.addEventListener("dragstart", dragstart))
// fills.forEach(fill=>fill.addEventListener("drag", drag))
fills.forEach(fill=>fill.addEventListener("dragend", dragend))

empties.forEach(empty=>empty.addEventListener("dragenter", dragenter))
empties.forEach(empty=>empty.addEventListener("dragover", dragover))
empties.forEach(empty=>empty.addEventListener("dragleave", dragleave))
empties.forEach(empty=>empty.addEventListener("drop", drop))

function dragstart(e) {
    e.dataTransfer.setData("text/plain", e.target.id)
    setTimeout(()=> e.target.classList.add("invisible"), 0)   
}

function dragend(e) {
    const in_empty_container = e.target.parentElement === empty_container
    if (!in_empty_container) {
        e.target.classList.remove("invisible")
    }
}

function dragover(e) {
    e.preventDefault()
    e.target.classList.add("drag-over")
}

function dragenter(e) {
    e.preventDefault()
    e.target.classList.add("drag-over")
}

function dragleave(e) {
    e.target.classList.remove("drag-over")
}

function drop(e) {
    const input = e.target.parentElement.children[0]
    e.target.classList.remove("drag-over")
    const id = e.dataTransfer.getData('text/plain')
    const draggable = document.getElementById(id)
    draggable.setAttribute("draggable", "false")
    e.target.parentElement.appendChild(draggable)
    draggable.classList.remove("invisible")
    input.value = draggable.getAttribute("data")
    e.target.remove()
    const num_items = document.querySelectorAll(".empty_container").length
    const fills_in_container = document.querySelectorAll("#drop_set .fill").length
    if (fills_in_container === num_items) {
        first_set.classList.add("hidden")
        done.classList.remove("hidden")
    }
}

function try_again(e) {
    e.preventDefault()
    location.reload()
}

// function gather_data() {
//     const targets = []
//     const inputs = []
//     document.getElementsByName("target[]").forEach(element => 
//       targets.push(element.value) 
//     )
//     document.getElementsByName("input[]").forEach(element => 
//       inputs.push(element.value) 
//     )
//     //Create an array
//     const post_inputs =  targets.map((target, i)=>{
//         return {
//             target,
//             input: inputs[i]
//         }
//     })

//     return {
//         activity: "Match Images",
//         author: document.getElementById("author").value,
//         guide_id: document.getElementById("guide_id_input").value,
//         taken_by: document.getElementById("user_id_input").value,
//         duration,
//         inputs: post_inputs
//     }

// }


// function submit(e) {
//     e.preventDefault()
    
//     fetch("http://localhost:5000/score_match", {
//         headers: {
//            'Content-Type': 'application/json',
//         },
//         method: "POST",
//         body: JSON.stringify(gather_data())
//     })
//     .then(res=>res.json())
//     .then(res=>{
//         give_feedback(res)
//     }) 
// }

// function give_feedback(res) {
//     done.classList.add("hidden")
//     alert(`You scored ${res.score} points in ${duration} seconds.`)

//     const check = `<span class="check" style="float:right;margin:5px">&#10004;</span>`
//     const cross = `<span class="cross" style="float:right;margin:5px">&#x274C;</span>`
                               
//     check_feedback.forEach((div, i) => {
//         const result = res.results[i].result
//         if (result == 1) div.innerHTML = check
//         else div.innerHTML = cross
//     })
// }

