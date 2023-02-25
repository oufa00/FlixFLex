@extends('layouts.app')
<link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet">
<meta name="_token" content="{{csrf_token()}}" />
<link rel="stylesheet" href="{{asset('css/films.css')}}">
@section('navbar')
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="{{route('films')}}">Films</a>
  <a class="navbar-brand" href="{{route('series')}}">Series</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item active">
        <a class="nav-link" href="{{route('films_favoris', ['id' => 0])}}">Mes favoris </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{route('films_top', ['id' => 0])}}">Top 5 films</a>
      </li>
     
      
    </ul>
    <form id='form' class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" id='search' placeholder="Chercher" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Cherch</button>
    </form>
  </div>
</nav>
@endsection
@section('content')

    
<div id="myNav" class="overlay">

    <!-- Button to close the overlay navigation -->
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  
    <!-- Overlay content -->
    <div class="overlay-content" id="overlay-content"></div>
    
    <a href="javascript:void(0)" class="arrow left-arrow" id="left-arrow">&#8656;</a> 
    
    <a href="javascript:void(0)" class="arrow right-arrow" id="right-arrow" >&#8658;</a>

  </div>
<main id="main"></main>
<div class="pagination">
    <div class="page" id="prev" style='color:black;'>Previous Page</div>
    <div class="current" id="current" style='color:black;'>1</div>
    <div class="page" id="next" style='color:black;'>Next Page</div>

</div>
<script src="http://code.jquery.com/jquery-3.3.1.min.js"
               integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
               crossorigin="anonymous">
</script>
  <script>
    const API_KEY = 'api_key=1cf50e6248dc270629e802686245c2c8';
const BASE_URL = 'https://api.themoviedb.org/3';
const API_URL = BASE_URL + '/discover/movie?sort_by=popularity.desc&'+API_KEY;
const IMG_URL = 'https://image.tmdb.org/t/p/w500';
const searchURL = BASE_URL + '/search/movie?'+API_KEY;
const main = document.getElementById('main');
const form =  document.getElementById('form');
const search = document.getElementById('search');

const prev = document.getElementById('prev')
const next = document.getElementById('next')
const current = document.getElementById('current')

var currentPage = 1;
var nextPage = 1;
var part = 0;
var retour = 1;
var prevPage = 3;
var lastUrl = '';
var totalPages = 100;
var ctpage = 0;
    getMovies(API_URL,0);

function getMovies(url,v) {
  lastUrl = url;
    fetch(url).then(res => res.json()).then(data => {
        console.log(data.results)
        if(data.results.length !== 0){
            if(v==0){
            showMovies(data.results.slice(0, 10));
            part=0;
          
            
            }
            else{
            showMovies(data.results.slice(10, 20));
            part=1;
           
            
            }
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

function getMoviesRetour(url,v) {
  lastUrl = url;
    fetch(url).then(res => res.json()).then(data => {
        console.log(data.results)
        if(data.results.length !== 0){
            if(v==0){
            showMovies(data.results.slice(0, 10));
            part=0;
          
            
            }
            else{
            showMovies(data.results.slice(10, 20));
            part=1;
           
            
            }
            currentPage = data.page;
            
            nextPage = currentPage + 1;
            prevPage = currentPage - 1;
            if((part==1)&&(currentPage==1))
            prevPage=1;
            totalPages = data.total_pages;
            ctpage=ctpage-1;
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
function favoris(id) {
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
                     films_id: id,
                     serie_id: null,
                    
                     
                  },
                  success: function(result){
                    
                  }});
}
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
        movieEl.innerHTML =' <button onclick="favoris('+id+')" > Ajouter au favoris </button>';

        movieEl.innerHTML +='<img src="'+src+'" alt="'+title+'">';
       
        movieEl.innerHTML += ' <div class="movie-info"><h3>'+title+'</h3><span class="'+getColor(vote_average)+'">'+vote_average+'</span></div>';
        movieEl.innerHTML += '<div class="overview"><h3>Overview</h3>'+overview+'<br/> <button class="know-more" id='+id+'> Détail </button></div>';
        main.appendChild(movieEl);
        


        document.getElementById(id).addEventListener('click', () => {
          console.log(id)
          openNav(movie)
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
const overlayContent = document.getElementById('overlay-content');

function openNav(movie) {
  let id = movie.id;
  fetch(BASE_URL + '/movie/'+id+'/videos?'+API_KEY).then(res => res.json()).then(videoData => {
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
        
        var content = '<h1 class="no-results">'+movie.original_title+'</h1><br/>'+embed.join('')+'<br/><div class="dots">'+dots.join('')+'</div>';
        
        
        overlayContent.innerHTML = content;
        activeSlide=0;
        showVideos();
      }else{
        overlayContent.innerHTML = '<h1 class="no-results">No Results Found</h1>';
      }
    }
  })
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

form.addEventListener('submit', (e) => {
    e.preventDefault();
    const searchTerm = search.value;
    
    if(searchTerm) {
        getMovies(searchURL+'&query='+searchTerm,0)
    }else{
        getMovies(API_URL,0);
    }

})

prev.addEventListener('click', () => {
  
  if((currentPage==1)&&(part==0)){

  }
  else{
    pageCallretour(currentPage,part);
  }
  
})

next.addEventListener('click', () => {
   
  if(nextPage <= totalPages){
    pageCall(nextPage,part);
  }
})

function pageCall(page,part){
   
  let urlSplit = lastUrl.split('?');
  let queryParams = urlSplit[1].split('&');
  let key = queryParams[queryParams.length -1].split('=');
 
  if(key[0] != 'page'){
    if(part==0)
    v=page-1;
    else
    v=page;
    let url = lastUrl + '&page='+v
    getMovies(url,1);
  }else{
    if(part==0)
    v=page-1;
    else
    v=page;
    key[1] = v.toString();
    let a = key.join('=');
    queryParams[queryParams.length -1] = a;
    let b = queryParams.join('&');
    let url = urlSplit[0] +'?'+ b
    if(part==0)
    getMovies(url,1);
    else
    getMovies(url,0);
   
  
  }
}

function pageCallretour(page,part){
  
   let urlSplit = lastUrl.split('?');
   let queryParams = urlSplit[1].split('&');
   let key = queryParams[queryParams.length -1].split('=');
  
     if(part==0)
     v=page-1;
     else
     v=page;
     key[1] = v.toString();
     let a = key.join('=');
     queryParams[queryParams.length -1] = a;
     let b = queryParams.join('&');
     let url = urlSplit[0] +'?'+ b
     if(part==0)
     getMoviesRetour(url,1);
     else
     getMoviesRetour(url,0);
    
   
   
 }
    </script>
@endsection
