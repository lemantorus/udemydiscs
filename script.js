
const formGenerated = false
const rf = document.querySelector(".rating-f")
const rs = document.querySelector(".rating-s")
const sendForm = document.querySelector('#sendForm')
var sendnext= true;
let requestBody = {
    'query':"",
    'category':"",
    'languages':[],
    'rating':{
        'from':"",
        'to':""
    },
    'students':''
}


function getRequestBody(){
    return JSON.stringify({
    'query':document.querySelector('#searchQuery').value,
    'category':document.querySelector('#categories').value,
    'languages':Array.from(document.querySelector('.langs-wrapper').querySelectorAll('input')).filter(input=>input.checked).map((item)=>item.value),
    'rating':{
        'from':+document.querySelector('.rating-f').value,
        'to':+document.querySelector('.rating-s').value
    }    })
}

function setupForm(languages,categories){
    function genOptionsHtml(list){
        return `${list.map((item)=>`<option value="${item}">${item}</option>`)}`
    }
    function generateCheckboxesHtml(list){
        return list.map((item)=>`<label><input type="checkbox" name="${item}" value="${item}">${item}</label>`).join("")
    }
    const catEl =document.querySelector('#categories')
    const langEl = document.querySelector('.langs-wrapper')
    catEl.innerHTML =genOptionsHtml(categories)
    langEl.innerHTML=generateCheckboxesHtml(languages)
    return 
    
}
var canLoad =true
let offset = 0
function addCoupons(data,add){
    let cardsWrapper = document.querySelector('.cards-wrapper')
    function diffDate(endDate){
        let now = new Date();
        let year = now.getFullYear();
        let month = String(now.getMonth() + 1).padStart(2, '0');
        let day = String(now.getDate()).padStart(2, '0');
        let date1 = new Date(`${year}-${month}-${day}`);
        endDate = new Date(endDate)
        return (endDate-date1)/1000/60/60/24
    }
    try{
    let content = ""
        if(data.length!=0){
        for (let i=0;i<data.length;i++){
            let item = data[i]
            //let endDate = diffDate(item['sale_end'])

            let content =`
            <div class="card">
            <img src="${item['image']}">
            <div class="card-content">
            <div class="card-title">${item['name']}</div>
            <div class="card-info-wrapper">
            <div class="card-info"><i class="fas fa-tags"></i>${item['category']}</div>
            <div class="card-info"><i class="fas fa-language"></i>${item['language']}</div>
            <div class="card-info"><i class="fas fa-star"></i>Rating: ${item['rating']}</div>
            <div class="card-info"><i class="fas fa-film"></i>${item['lectures']} Lectures</div>
            <div class="card-info"><i class="fas fa-user-graduate"></i>${item['students']} Students</div>
            </div>
            <div class="card-price">
                <del>$${item['price']}</del>
                <span>$${item['sale_price']}</span>
            </div>
            <a href="${item['url']}" class="card-button">Go to Course</a>
        </div>
    </div>
            `
            if (add)
                {
            cardsWrapper.innerHTML += content
        }
        else{
            cardsWrapper.innerHTML=content
            add = true;
        }
        }}
        else{
            canLoad = false;
        }
    }
    catch{
        canLoad = true

        return false}
        
    }
function getData(firsttime,formTrue=false){
    endpoint = firsttime?'api.php':'search.php'
        return fetch(`${endpoint}?offset=${offset}`,{
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: getRequestBody()
        }).then(response => {
            return response.json()
        }).then(response=>{
            if(formTrue){
                const languages =response['languages']
                const categories = response['categories']
                setupForm(languages,categories)
            }
            addCoupons(response['data'],true)
            offset+=10
            if(response['data'].length==0){
                canLoad = false}
            else{
                canLoad = true
            }
            return true
        })
    }
window.addEventListener('scroll',()=>{
    if (
        window.innerHeight+window.scrollY>=document.body.offsetHeight*.95&&canLoad===true

    ){
        canLoad=false
        getData(false)
    }
})

sendForm.addEventListener('click',(e)=>{
    canLoad = true;
    e.preventDefault()
    offset= 0
    fetch(`search.php?offset=${offset}`,{
        method:'POST',
        headers:{'Content-Type':"application/json"},
        body:getRequestBody()
    }).then(result=>(result.json())).then(result=>{
        addCoupons(result['data'],false)
        offset+=10
    })
})
document.addEventListener('DOMContentLoaded',()=>{
    getData(true,true)
    setupForm()

    })
    const menuToggle = document.querySelector('.menu-toggle');
    const nav = document.querySelector('.nav');
    
    menuToggle.addEventListener('click', () => {
        nav.classList.toggle('active');}
    )