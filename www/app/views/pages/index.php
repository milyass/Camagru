<?php require APPROOT . '/views/inc/header.php'; ?>
<div class="container">
    <div class="row">
    <div class="card-deck">
    <?php foreach($data['posts'] as $post) : ?>
<div class="col-sm-6 col-lg-4 p-2">
    <div class="card">
        <div class="view overlay zoom">
        <img class="card-img-top" src="<?php echo URLROOT .'/img/'.$post->imageid; ?>" alt="photo">
        <div class="mask rgba-white-slight"></div>
        </div>
        <div class="card-body flex-column d-flex">
        <h5><?php echo $post->names; ?></h5>
            <div class="row p-2">
            &nbsp;
            <a id="like" class="like" name="<?php echo $post->id; ?>">
            <?php if($post->count == 0):?>
            <i id="<?php echo $post->id; ?>" class="far fa-heart"></i>
            <?php else: ?>
            <i id="<?php echo $post->id; ?>" class="fas fa-heart"></i>
            <?php endif?>
            <?php echo $post->count; ?></a>
            <a id="allcomments" ><i id="<? echo $post->id; ?>" class="fas fa-comment"></i></a>
            </div>
            <div class="grp jumbotron">
            <ul class="list-group list-group-flush <? echo $post->id; ?>">
            </ul>
            </div>
            <div class="row">
            <div class="col">
            <?php if(isLoggedIn()) :?>
            <textarea type="text" name="comment" id="<?php echo $post->id; ?>" class="card-text md-textarea form-control" name="comment"></textarea>
            <?php endif ?>
            </div>
            </div>
        </div>
        <div class="card-footer">
        <small class="text-muted">Created on  <?
        echo strftime('%A %b %e at %R %P',strtotime($post->created_at));
        ?></small>
        </div>
    </div>
</div>
  <?php endforeach; ?>
  </div>
  </div>
  <div class="row justify-content-md-center">
  <nav aria-label="Page navigation">
  <ul class="pagination">
  <? for ($page=1;$page<=$extra['number_of_pages'];$page++) : ?>
    <li class="page-item pt-5"><a class="page-link white-text primary-color-dark" href="<?php echo URLROOT .'/pages/index/'.$page?>" ><?php echo $page; ?></a></li>
  <?php endfor;?>
  </ul>
  </nav>
  </div>
</div>
<script>
////////////Like script
var choiceArray = document.querySelectorAll('a[id=like]');
for (var i = 0; i < choiceArray.length; i++) {
    choiceArray[i].addEventListener('click', function(e){
        var pic = this.firstElementChild.id;
        likepic(pic);
    });
}

function likepic(pic){
    var formdata = new FormData();
    formdata.append('PictureId', pic);
    var xhr = new XMLHttpRequest();
    xhr.open('POST','http://localhost/posts/liked');
    xhr.onload = function(e){
        if(this.status == '200'){
            location.reload();
        }
    }
    xhr.send(formdata);
}
///////////////////////////////
///////////////////////////// comment script
var cmntArray = document.querySelectorAll('textarea[name=comment]');
for (var i = 0; i < cmntArray.length; i++) {
    cmntArray[i].addEventListener('keydown', function(e){
        if (e.keyCode == 13){
            var cid = this.id;
            var cmnt = this.value;
            commentFunc(cid, cmnt);
            this.value = '';
        }
    });
}

function commentFunc(cid,cmnt){
    var formdata = new FormData();
    formdata.append('commentID', cid);
    formdata.append('comment',cmnt);

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://localhost/posts/comment/');
    xhr.onload = function(e){
        if(this.status == 200){
            if(xhr.responseText == "pls login" || xhr.responseText == "error")
                return;
            getcomments(cid);
        }
    } 
    xhr.send(formdata);
}
/////////////////////////////// all comments
var allcmntsArray = document.querySelectorAll('a[id=allcomments]');
for (var i = 0; i < allcmntsArray.length; i++) {
    allcmntsArray[i].addEventListener('click', function(e){
        var cmntid = this.firstElementChild.id;
        getcomments(cmntid);
    }
    );
}

function getcomments(cmntid){
    var listcomments  = document.getElementsByClassName(cmntid);
    var formdata = new FormData();
    formdata.append('commentID', cmntid);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://localhost/posts/getcomment/');
    xhr.onload = function(e){
        if(this.status == 200){
            if(xhr.responseText == "error")
                return;
            var comments = JSON.parse(this.responseText);
            var ch = '';
            for (c in comments)
            {
                ch += '<li class="list-group-item"><strong>'+comments[c].names +'</strong>: '+ comments[c].message+'</li>';
            }
            listcomments[0].innerHTML = ch;
            if(listcomments[0].innerHTML != '')
                listcomments[0].parentElement.style.display = "block";
        }
    } 
    xhr.send(formdata);
}
</script>
<?php require APPROOT . '/views/inc/footer.php'; ?>
