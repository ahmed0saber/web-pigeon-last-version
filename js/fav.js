/*  FAV or REC  */
faved = document.getElementsByClassName('faved');
not_fav = document.getElementsByClassName('not-fav');

function show_fav(){
    for (var i = 0; i < not_fav.length; i++){
        not_fav[i].parentElement.style.display = 'none';
    }
}
function show_rec(){
    for (var i = 0; i < not_fav.length; i++){
        not_fav[i].parentElement.style.display = 'block';
    }
}

/*  ADD TO FAV  */
function add_to_fav(element, id){
    if(element.classList.contains("faved")){
        element.classList.remove('faved');
        element.classList.add('not-fav');
    }else{
        element.classList.remove('not-fav');
        element.classList.add('faved');
    }

    document.getElementById("fav_form_txt").value = id;
    document.getElementById("fav_form_btn").click();

    console.log("done");
}