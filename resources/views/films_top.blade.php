@extends('layouts.app')
<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
<meta name="_token" content="{{csrf_token()}}" />
<link rel="stylesheet" href="{{asset('css/films.css')}}">
@section('navbar')
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="{{route('films')}}">Films</a>
  <a class="navbar-brand" href="{{route('series')}}">Series</a>


  <div class="collapse navbar-collapse " id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
            <a class="nav-link" href="{{route('films_favoris', ['id' => $categorie])}}">Mes favoris </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{route('films_top', ['id' => $categorie])}}">Top 5 @if($categorie==1) series @else films @endif</a>
          </li>
     
      
    </ul>
   
  </div>
</nav>
@endsection
@section('content')

<h1>Top 5 @if($categorie==1) series @else films @endif</h1>

 
<hr>
<div id="myNav" class="overlay">

    <!-- Button to close the overlay navigation -->
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  
    <!-- Overlay content -->
    <div class="overlay-content" id="overlay-content"></div>
    
    <a href="javascript:void(0)" class="arrow left-arrow" id="left-arrow">&#8656;</a> 
    
    <a href="javascript:void(0)" class="arrow right-arrow" id="right-arrow" >&#8658;</a>

  </div>
<main id="main"></main>

<script src="http://code.jquery.com/jquery-3.3.1.min.js"
               integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
               crossorigin="anonymous">
               </script>
<script>  
const categorie = {!! json_encode($categorie) !!};
if(categorie==1)
var categorie_general='tv';
else
 categorie_general='movie';
function favoris(id) {
    var iddeltetv=null;
  var iddeltemovie=null;
  if(categorie_general=='tv'){
    iddeltetv=id;
  }
  else{
    iddeltemovie=id;
  }
  $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                  }
              });
  jQuery.ajax({
                  url: "{{ url('/favoris') }}",
                  method: 'post',
                  data: {
                    type:'add',
                     films_id: iddeltemovie,
                     serie_id: iddeltetv,
                    
                     
                  },
                  success: function(result){
                     alert('jj');
                  }});
}


const overlayContent = document.getElementById('overlay-content');

function opennavbar(movie) {
  //alert(movie);
  let id = movie.id;
 
  fetch(BASE_URL + '/'+categorie_general+'/'+id+'/videos?'+API_KEY).then(res => res.json()).then(videoData => {
    console.log(videoData);

    if(videoData){
        
      document.getElementById("myNav").style.width = "100%";
      if(videoData.results.length > 0){
        var embed = [];
        var dots = [];
        videoData.results.forEach((video, idx) => {
          let {name, key, site} = video
          if(site == 'YouTube'){
              idv=idx+1;
            embed.push('<iframe width="560" height="315" src="https://www.youtube.com/embed/'+key+'" title="'+name+'" class="embed hide" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
            dots.push('<span class="dot">'+idv+'</span>');
          }
        })
        
        var content = '<h1 class="no-results"></h1><br/>'+embed.join('')+'<br/><div class="dots">'+dots.join('')+'</div>';
        
        
        overlayContent.innerHTML = content;
        activeSlide=0;
        showVideos();
      }else{
        overlayContent.innerHTML = '<h1 class="no-results">No Results Found</h1>';
      }
    }
  })
}
const API_KEY = 'api_key=1cf50e6248dc270629e802686245c2c8';
const BASE_URL = 'https://api.themoviedb.org/3';  
const main = document.getElementById('main');
const IMG_URL = 'https://image.tmdb.org/t/p/w500';
const API_URL = BASE_URL + '/'+categorie_general+'/top_rated?'+API_KEY;


getMovies(API_URL);

function getMovies(url) {
  lastUrl = url;
    fetch(url).then(res => res.json()).then(data => {
        console.log(data.results)
        if(data.results.length !== 0){
            showMovies(data.results.slice(0, 5));
            
            currentPage = data.page;
            
            nextPage = currentPage + 1;
            prevPage = currentPage - 1;
            if((part==1)&&(currentPage==1))
            prevPage=1;
            totalPages = data.total_pages;
            ctpage=ctpage+1;
           current.innerText = ctpage;
           
           
           
            if((currentPage <= 1)&&(part==0)){
              prev.classList.add('disabled');
              next.classList.remove('disabled')
            }else if(currentPage>= totalPages){
              prev.classList.remove('disabled');
              next.classList.add('disabled')
            }else{
              prev.classList.remove('disabled');
              next.classList.remove('disabled')
            }

            tagsEl.scrollIntoView({behavior : 'smooth'})

        }else{
            main.innerHTML= '<h1 class="no-results">No Results Found</h1>';
        }
       
    })

}
    main.innerHTML = '';
    function showMovies(data) {
    main.innerHTML = '';
    var src='';
    data.forEach(movie => {
      
        const {title, poster_path, vote_average, overview, id} = movie;
        const movieEl = document.createElement('div');
        movieEl.classList.add('movie');
        if(poster_path==null){
        src='http://via.placeholder.com/1080x1580';
        }
        else{
        src = IMG_URL+''+poster_path;
        }
        if(categorie_general=='tv')
        title1=movie.name;
        else
        title1=movie.title;
        movieEl.innerHTML =' <button onclick="favoris('+id+')" > Ajouter au favoris </button>';

        movieEl.innerHTML +='<img src="'+src+'" alt="'+title1+'">';

        movieEl.innerHTML += ' <div class="movie-info"><h3>'+title1+'</h3><span class="'+getColor(vote_average)+'">'+vote_average+'</span></div>';
        movieEl.innerHTML += '<div class="overview"><h3>Overview</h3>'+overview+'<br/> <button class="know-more" id='+id+'> D??tail </button></div>';
        main.appendChild(movieEl);
        


        document.getElementById(id).addEventListener('click', () => {
          console.log(id)
          opennavbar(movie)
        })
    })
}
     function getColor(vote) {
    if(vote>= 8){
        return 'green'
    }else if(vote >= 5){
        return "orange"
    }else{
        return 'red'
    }
}



var activeSlide = 0;
var totalVideos = 0;

function showVideos(){
    
  let embedClasses = document.querySelectorAll('.embed');
  let dots = document.querySelectorAll('.dot');

  totalVideos = embedClasses.length; 
  embedClasses.forEach((embedTag, idx) => {
    if(activeSlide == idx){
      embedTag.classList.add('show')
      embedTag.classList.remove('hide')

    }else{
      embedTag.classList.add('hide');
      embedTag.classList.remove('show')
    }
  })

  dots.forEach((dot, indx) => {
    if(activeSlide == indx){
      dot.classList.add('active');
    }else{
      dot.classList.remove('active')
    }
  })
}
function closeNav() {
  document.getElementById("myNav").style.width = "0%";
}

const leftArrow = document.getElementById('left-arrow')
const rightArrow = document.getElementById('right-arrow')

leftArrow.addEventListener('click', () => {
  if(activeSlide > 0){
    activeSlide--;
  }else{
    activeSlide = totalVideos -1;
  }

  showVideos()
})

rightArrow.addEventListener('click', () => {
  if(activeSlide < (totalVideos -1)){
    activeSlide++;
  }else{
    activeSlide = 0;
  }
  showVideos()
})
</script>
@endsection