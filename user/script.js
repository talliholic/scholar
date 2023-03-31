const teacher_menu = `
<h1 class="center-nomargin-title">Scholar</h1>
<nav class="row">
    <a href="/scholar/user">Account</a>
    <a href="/scholar/classrooms">Classrooms</a>
    <a href="/scholar/study_guides">Study Guides</a>
    <a href="/scholar/flow_charts">Flow Charts</a>
    <a href="/scholar/activities">Activities</a>
    <a href="/scholar/tutorials/?page=teachers">Tutorials</a>
    <a id="logout-btn" href="">Log out</a>
</nav>
`;

const student_menu = `
<h1 class="center-nomargin-title">Scholar</h1>
<nav class="row">
    <a href="/scholar/user">Account</a>
    <a href="/scholar/activities">Activities</a>
    <a href="/scholar/stats">Stats</a>
    <a href="/scholar/tutorials/?page=students">Tutorials</a>
    <a id="logout-btn" href="">Log out</a>
</nav>
`;

const header = document.querySelector("header");
const legend = document.querySelector("legend");
const name_div = document.querySelector("#name_div")
const form = document.querySelector("form")
const role_input = document.querySelector("#role_input")
const main = document.querySelector("main")

form.addEventListener("submit", update)

fetch("http://localhost:5000/read_user", {
  method:"POST",
  body
})
  .then((res) => res.json())
  .then((res) => {
    render_menu(res.role);
    personalize_fieldset(res);
  });

function render_menu(role) {
  if (role === "Teacher") header.innerHTML = teacher_menu;
  else header.innerHTML = student_menu;
  const logout_btn = document.querySelector("#logout-btn")
  logout_btn.addEventListener("click", logout)
  password_button_handler()
}

function password_button_handler() {
  const info_div = document.querySelector("#info_div")
  const password_button = document.querySelector("#password_button")
  const cancel_button = document.querySelector("#cancel_button")

  password_button.addEventListener("click", (e) => {
    e.preventDefault()
    info_div.classList.add("hidden")
    form.classList.remove("hidden")
    e.target.classList.add("hidden")
  })

  cancel_button.addEventListener("click", (e) => {
    e.preventDefault()
    info_div.classList.remove("hidden")
    form.classList.add("hidden")
    password_button.classList.remove("hidden")
  })
}

function personalize_fieldset(res) {
  legend.textContent = res.name
  name_div.textContent = res.name
  role_input.value = res.role
  main.classList.remove("hidden")
}

function update(e) {
  e.preventDefault()
  const update_data = new FormData(e.target)
  fetch("http://localhost:5000/update_user", {
    method: "POST",
    body: update_data
  })
  .then((res) => res.json())
  .then((res) => {
    if (res.success) {
      alert("You have udated your username!")
      location.reload()
    } else {
      alert("Your request was not successful")
    }
  })
}

function logout(e) {
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
