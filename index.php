<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Card</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/styles.css">
</head>
<body>
    <div id="mainWrapper">
    <form action="search.php" id="searchingForm" method="POST">
        <div class="form-searchQ-wrapper">
        <label for="">Find for</label>
        <input type="text" id="searchQuery" name="searchQuery">
        </div>
    <div class="form-cat-wrapper">
        <label for="">Category</label>
        <select name="categories" id="categories">
        </select>
    </div>
        <div id="fslg">
        <label>Languages</label>
        <div class="langs-wrapper"></div>
        </div>
<div class="form-rating-wrapper">
        <label for="">Rating</label>
        <div class="div-rating-nums">
            <p>From</p><input class='rating-f'type="number" min="0" max="5"><p>to</p><input class="rating-s" type="number" min="0" max="5">
            
        </div>
        </div>
        <div class="form-students-wrapper">
        <label for="students">Students</label>
        <input type="number" id="students" name="students" min="0">
        </div>
        <input type="submit" value="SNED" id="sendForm"></iinput>
    </form>
    <div class="cards-wrapper">
       </div>
    </div>
    </div>
<script>
    const formGenerated = false
    const categories = ['Development','Data-scrince']
    const languages = ['English','Spanish','Russian','Germany','French','Britan']
    const rf = document.querySelector(".rating-f")
    const rs = document.querySelector(".rating-s")
    const sendForm = document.querySelector('#sendForm')
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
        return {
        'query':document.querySelector('#searchQuery').value,
        'category':document.querySelector('#searchQuery').value,
        'languages':Array.from(document.querySelector('.langs-wrapper').querySelectorAll('input')).filter(input=>input.checked).map((item)=>item.value),
        'rating':{
            'from':document.querySelector('.rating-f').value,
            'to':document.querySelector('.rating-s').value
        },
        'students':document.querySelector('#students').value
    }
    }
    function checkRating(){
            if(rs.value<rf.value){rs.value=rf.value}

    }
    function setupForm(){
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
    let canLoad =true
    let offset = 0
    function addCoupons(data){
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
        let content = ""
            for (let i=0;i<data.length;i++){
                let item = data[i]
                //let endDate = diffDate(item['sale_end'])

                let content =`
                <div class="card">
                <img src="${item['image']}">
                <div class="card-content">
                <div class="card-title">${item['name']}</div>
                <div class="card-info"><i class="fas fa-tags"></i>${item['category']}</div>
                <div class="card-info"><i class="fas fa-language"></i>${item['language']}</div>
                <div class="card-info"><i class="fas fa-star"></i>Rating: ${item['rating']}</div>
                <div class="card-info"><i class="fas fa-film"></i>${item['lectures']} Lectures</div>
                <div class="card-info"><i class="fas fa-user-graduate"></i>${item['students']} Students</div>
                <div class="card-price">
                    <del>$${item['price']}</del>
                    <span>$${item['sale_price']}</span>
                </div>
                <a href="${item['url']}" class="card-button">Go to Course</a>
            </div>
        </div>
                `
                cardsWrapper.innerHTML += content
            }
        }
    function getData(){
        if (formGenerated==false){
}
            fetch(`api.php?offset=${offset}`,{
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `offset=${offset}`
            }).then(response => {
                return response.json()
            }).then(response=>{
                console.log('go')
                addCoupons(response)
                offset+=10
                canLoad=true
            })
        }
    window.addEventListener('scroll',()=>{
        if (
            window.innerHeight+window.scrollY>=document.body.offsetHeight*.95&&canLoad===true
        ){
            canLoad=false
            getData()
        }
    })
    rf.addEventListener('input',()=>checkRating())
    rs.addEventListener('input',()=>checkRating())
    sendForm.addEventListener('click',(e)=>{
        e.preventDefault()
        fetch('search.php',{method:'POST',body:{'allah':'loh'}}).then(result=>(result.text())).then(result=>alert(result))
    })
    document.addEventListener('DOMContentLoaded',()=>{
        setupForm()
        getData()

        })
</script>
</body>
</html>