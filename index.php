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
        <input type="submit" value="FIND" id="sendForm"></iinput>
    </form>
    <div class="sorting-wrapper">
    </div>
    <div class="cards-wrapper">
       </div>
    </div>
    </div>
    <script src='script.js'></script>
</body>
</html>