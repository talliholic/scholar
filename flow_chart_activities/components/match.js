const descriptions = document.querySelectorAll(".description")
const images = document.querySelectorAll(".image")
const select = document.getElementById("select")

let description_clicked = false
let image_clicked = false

descriptions.forEach(description => description.addEventListener("click", description_handler))
images.forEach(image => image.addEventListener("click", image_handler))
select.addEventListener("click", select_handler)

function description_handler(e) {
    descriptions.forEach(description => description.classList.remove("clicked"))
    e.target.classList.add("clicked")
}

function image_handler(e) {
    images.forEach(image => image.classList.remove("clicked"))
    e.target.classList.add("clicked")
}

function select_handler() {
    const clicked = document.querySelectorAll(".clicked")
    clicked.forEach(elem => elem.className = ".selected")
}