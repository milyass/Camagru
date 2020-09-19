<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="row mb-3">
    <div class="col-md-6">
      <h1>Upload a Picture</h1>
      <hr>
    </div>
</div>
<div class="row">
<!-- Photo CARD-->
<div  class="p-3 col-md-8">
<div class="card card-body">
    <video class="embed-responsive embed-responsive-16by9" id="video"> Stream Not Available check If Camera working </video>
    <hr>
    <canvas class="p-2 embed-responsive embed-responsive-16by9" width="500" height="500" id="canvas">
    </canvas>
    <div class="row justify-content-center">
    <div class="col-6">
    <button id="photobutton" class="btn btn-info snap btn-block">Snap IT!</button>
    </div>
    <div class="col-6">
    <button id="savebutton" onclick="Saveit()" class="btn btn-primary snap btn-block">Save IT!</button>
    </div>
    </div>
    <br>
    <div class="row justify-content-center p-2">
            <button class="btn btn-dark" onclick="clearfunc()"><i class="fas fa-sync"></i>Clear IT!</button>
    </div>
    <br>
    <div class="jumbotron jumbotron-fluid">
       <div class="d-flex justify-content-center">
            <select  class="custom-select col-6" id="photo-filter">
                    <option value="none">Normal</option>
                    <option value="grayscale(100%)">Black and White</option>
                    <option value="sepia(100%)">Sepia</option>
                    <option value="invert(100%)">Invert</option>
                    <option value="hue-rotate(60deg)">Hue</option>
                    <option value="saturate(5)">Saturate</option>
                </select>
       </div>
    <div class="row p-4 justify-content-center">
    <div class="col-2">
    <input type="radio" name="sticker" value="KID"><img id="sticker" class="embed-responsive embed-responsive-16by9 sticker" src="http://localhost/public/img/Stickers/kid.png">
    </div>
    <div class="col-2">
    <input type="radio"  name="sticker" value="PEPE"><img id="sticker" class="embed-responsive embed-responsive-16by9 sticker" src="http://localhost/public/img/Stickers/pepe.png">
    </div>
    <div class="col-2">
    <input type="radio"  name="sticker" value="PEPA"><img id="sticker" class="embed-responsive embed-responsive-16by9 sticker" src="http://localhost/public/img/Stickers/pepa.png">
    </div>
    <div class="col-2">
    <input type="radio"  name="sticker" value="THUMBS"><img id="sticker" class="embed-responsive embed-responsive-16by9 sticker" src="http://localhost/public/img/Stickers/thumbs.png">
    </div>
    <div class="col-2">
    <input type="radio"  name="sticker" value="ANGRY"><img id="sticker" class="embed-responsive embed-responsive-16by9 sticker" src="http://localhost/public/img/Stickers/angry.png">
    </div>
    <div class="col-2">
    <input type="radio"  name="sticker" value="MARVIN"><img id="sticker" class="embed-responsive embed-responsive-16by9 sticker" src="http://localhost/public/img/Stickers/marvin.png">
    </div>
    </div>
    </div>
    <button class="btn btn-secondary" onclick="ShowUpload()">Upload IT?</button>
    <div style="display: none" id="UploadDiv">
        <hr>
        <div class="custom-file">
            <input type="file" name="file" class="custom-file-input">
            <label class="custom-file-label"></label>
        </div>
        <button  onclick="uploadimage()" class="btn deep-blue-gradient" type="submit"><i class="fas fa-upload"></i>Upload</button>
        <div id="photocard"></div>
    </div>
</div>
</div>
<!-- END PHOTO CARD -->
<!-- Thumbnail CARD-->
<div class="p-3 p-12 col-md-4 col-sm-12">
<div class="card card-body" id="listImg">
</div>
</div>
<!-- END Thumbnail CARD-->
<script>
// toggle button
function ShowUpload() 
    {
        var div = document.getElementById('UploadDiv');
        div.style.display = div.style.display == "none" ? "block" : "none"; 
    }
// clear
function clearfunc(){
    var canvas = document.getElementById("canvas");
    var ctx = canvas.getContext("2d");
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    document.getElementById("savebutton").disabled = true;
}
// end clear

//// Save it
function Saveit(){

var taken = document.getElementById('canvas');
var imgbase = taken.toDataURL('image/URL');
formData = new FormData();
formData.append('takenpic',imgbase);
var xhr = new XMLHttpRequest();
xhr.open('POST', 'http://localhost/posts/saveit',true);
xhr.onload = function(e){
    if(this.status == 200){
        if(this.responseText == "Error"){
            alert("Error");
        }
        loadUsers();
    }
}

xhr.send(formData);
////xhr send the image base64

}
 
//// END THE SNAP

/// xhr request for side bar
function loadUsers(){
    const listImg = document.getElementById('listImg');
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://localhost/posts/jsonreturn', true);
    xhr.onload = function(e){
        
        if(this.status == 200){
            var images = JSON.parse(this.responseText);
            var ch = '';
            for (img in images)
            {
                ch += '<a id="deletePost"><i id="'+images[img].id+'" class="fas fa-trash-alt"></i></a>';
                ch += '<img class="embed-responsive embed-responsive-16by9" src="<?php echo URLROOT .'/img/';?>' + images[img].imageid + '">';
            }
            listImg.innerHTML = ch;
            var aArray = document.querySelectorAll('a[id=deletePost]');
            for (var i = 0; i < aArray.length; i++) {
                aArray[i].addEventListener('click', function(e){
                    var pic = this.firstElementChild.id;
                    deletepic(pic);
                });
            }
        }
    }
    xhr.send();
    
}
///// end xhr request for sidebar

/// WINDOW ONLOAD FUNCTIONS
window.onload = function() {
    // loadusers function for sidebar pics
    loadUsers();
    document.getElementById("savebutton").disabled = true;
    document.getElementById("photobutton").disabled = true;
};
///// END WINDOW ONLOAD

/// UPLOAD IMAGE TO CANVAS VIA XHR
function uploadimage(){
    var input = document.querySelector('input[type=file]');
    var file = input.files[0];
    var sticker = document.querySelector('input[type=radio]:checked');
    if(!sticker)
    {
        alert("please choose an effect");
        return;
    }
    var formData = new FormData();
    formData.append("file",file);
    formData.append("sticker",sticker.value);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://localhost/posts/upload');
    xhr.onload = function(e){
        if(this.status == 200 ){
            var canvas = document.getElementById("canvas");
            var ctx = canvas.getContext("2d");
                canvas.width = 690;
                canvas.height = 518;
            if(xhr.responseText == "File Too Large or Empty" || xhr.responseText == "FILE ERROR" || xhr.responseText == "Invalid File")
            {
                alert(xhr.responseText);
                ctx.clearRect(0, 0, canvas.width, canvas.height);
            }
            var image = new Image();
            image.onload = function(){
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                ctx.drawImage(image,0, 0,690,518);
                    if(document.getElementById("savebutton").disabled = true){
                        document.getElementById("savebutton").disabled = false;
                    }
            }
            image.src = xhr.responseText;
        }
    }
    xhr.send(formData);
}
/////// END UPLOAD

///////// START CAMERA SCRIPT
var width = 690, 
    height = 0,
    streaming = false;
    //DOM ELEM
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    /// get stream
    if (navigator.mediaDevices === undefined) {
  navigator.mediaDevices = {};
}
if (navigator.mediaDevices.getUserMedia === undefined) {
  navigator.mediaDevices.getUserMedia = function(constraints) {
    var getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
    if (!getUserMedia) {
      return Promise.reject(new Error('getUserMedia is not implemented in this browser'));
    }
    return new Promise(function(resolve, reject) {
      getUserMedia.call(navigator, constraints, resolve, reject);
    });
  }
}
navigator.mediaDevices.getUserMedia({video: true, audio: false})
.then(function(stream){
    if ("srcObject" in video)
        video.srcObject = stream;
    else {
        video.src = window.URL.createObjectURL(stream);
    }
    video.onloadedmetadata = function(e) {
        video.play();
    };
    })
    .catch(function(err){
    console.log(`ERROR:  ${err}`);
    });
    video.addEventListener('canplay',function(e){
        if(!streaming){
            //set video canvas height
            height = video.videoHeight / (video.videoWidth / width);
            video.setAttribute('width', width);
            video.setAttribute('height', height);
            canvas.setAttribute('width', width);
            canvas.setAttribute('height', height);
            streaming = true;
        }
    }, false);

/// filter 
var filter = 'none';
var photofilter = document.getElementById('photo-filter');
photofilter.addEventListener('change', function(e){
   filter = e.target.value;
   video.style.filter = filter;
});
/////// END CAMERA SCRIPT

/// take photo button
photobutton.addEventListener('click',function(e){
snapPicture();
e.preventDefault();
}, false);

var choiceArray = document.querySelectorAll('input[type=radio]');
for (var i = 0; i < choiceArray.length; i++) {
    choiceArray[i].addEventListener('click', function(e){
        document.getElementById("photobutton").disabled = false;
    });
}

//////////////////////////////////////////////////////////////////////////Take Picture
function snapPicture(){    
    var sticker = document.querySelector('input[type=radio]:checked');
    var names = ['KID','THUMBS','PEPE','PEPA','ANGRY','MARVIN'];
        if(names.indexOf(sticker.value) == -1){ 
            alert("Error");
            return;
        }
        if(!sticker)
        {
            alert("please choose an effect");
            return;
        }
    //create canvas
    var context = canvas.getContext('2d');
    context.clearRect(0, 0, 500,500);
    if(width && height){
        canvas.width = width;
        canvas.height = height;
        /// draw image
        context.drawImage(video, 0,0, width, height);
        if(document.getElementById("savebutton").disabled = true){
            document.getElementById("savebutton").disabled = false;
        }
    }
    var imgurl = canvas.toDataURL('image/URL');
    var formData = new FormData();
        formData.append("imgurl",imgurl);
        formData.append("sticker", sticker.value);
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'http://localhost/posts/snap',true);
        xhr.onload = function(e){
            if(this.status == 200){
                if(this.responseText == 'Error'){
                    var ctx = canvas.getContext("2d");
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    return;
                }
                else {
                    var ctx = canvas.getContext("2d");
                    var image = new Image();
                    image.onload = function(){
                        ctx.filter = filter;
                        ctx.clearRect(0, 0, canvas.width, canvas.height);
                        ctx.drawImage(image,0, 0,canvas.width,canvas.height);
                    }
                    image.src = xhr.responseText;
                }
            }
        }
        xhr.send(formData);
}
//////////////////////////////////////////////////////////////////////////end take picture

/////////////////////////////////////////////////DELETE

function deletepic(pic){
var form = new FormData;
form.append('pictureid',pic);
var xhr = new XMLHttpRequest();
xhr.open('POST', 'http://localhost/posts/deleteit');
xhr.onload = function (){
    if(this.status == 200){
        loadUsers();
    }
}
xhr.send(form);
}
////////////////////////////////////////////// DELETE

</script>

<?php require APPROOT . '/views/inc/footer.php'; ?>
