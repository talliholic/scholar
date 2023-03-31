const urlParams = new URLSearchParams(window.location.search)
const grade_level = urlParams.get('grade_level')
const subject = urlParams.get('subject')

const subjects_div = document.getElementById("subjects")
const activities = document.getElementById("activities")

if (!subject) {
    if( grade_level) {
        fetch(`https://ivanperez.tech/get_subjects?grade_level=${grade_level}`)
        .then(res=>res.json())
        .then(res=>{
            subjects_div.style.display = "flow" 
            activities.style.display = "none" 
            render_subjects(res)
        })
    }    

} else {
    fetch(`https://ivanperez.tech/get_activities?grade_level=${grade_level}&subject=${subject}`)
    .then(res=>res.json())
    .then(res=>{
        subjects_div.style.display = "none"
        activities.style.display = "flow"
        render_activities(res)
    })
}

function render_subjects(res) {
    let html = `<h2 class="activities_title">${grade_level} Subjects</h2>`
    html += res.map(subject => `
    <fieldset>
        <legend>${subject.subject}</legend>
        <a class="link" href="?grade_level=${grade_level}&subject=${subject.subject}">
            <img height="150" src="${subject.img_path}" alt="subject"/>
        </a>
    </fieldset>
    `).join("")

    subjects_div.innerHTML = html
}

function render_activities({study_guides, flow_charts}) {
   subjects_div.style.display = "none"
   let html = `<h2 class="activities_title">${grade_level} ${subject} Activities</h2>` 
   html += study_guides.map(activity=>`
        <fieldset>
            <legend>${activity.title}</legend>
            <a class="link" href="?page=Review&id=${activity.id}">
                <img src="${activity.img_path}" height="150"/>
            </a>
            <span><b>Items: </b>${activity.num_activities}</span>
        </fieldset>
    `).join("")
    html += flow_charts.map(activity=>`
        <fieldset>
            <legend>${activity.title}</legend>
            <a class="link" href="/scholar/flow_chart_activities?page=Firsts&id=${activity.id}">
                <img src="${activity.img_path}" height="150"/>
            </a>
            <span><b>Items: </b>${activity.num_activities}</span>
        </fieldset>      
    `).join("")
    activities.innerHTML = html 
}
