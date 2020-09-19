<?php require APPROOT . '/views/inc/header.php'; ?>
<h1>My Photos</h1>
<div class="container">
<?php if(!empty($data['posts'])) : ?> 
    <div class="row">
    <div class="card-deck">
    <?php foreach($data['posts'] as $post) : ?>
        <div class="col-sm-12 col-md-4 p-2">
            <div class="card">
                <div class="view overlay">
                <img class="card-img-top" src="<?php echo URLROOT .'/img/'.$post->imageid; ?>" alt="photo">
                <div class="mask rgba-blue-light"></div>
                </div>
                <div class="card-footer">
                <a id="deletePost"><i id="<?php echo $post->id; ?>" class="fas fa-trash-alt"></i></a> 
                <small class="text-muted">Created on  <? 
                echo strftime('%A %b %e at %R %P',strtotime($post->created_at));
                ?></small>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
  </div>
<?php else :?>
  <div class="row">
    <div class="col">
      <div class="card card-body bg-info">
        <h1><i class="far fa-frown"></i>No Pictures YET</h1>
        <hr>
        <h5>You can <b>take</b> or <b>upload</b> Pictures by pressing this Link<i class="fas fa-arrow-right"></i><a href="<?php echo URLROOT; ?>/posts/create"><i class="fas fa-camera-retro"></i> Take a Picture</a><h5>
      </div>
    </div>
  </div>
  <?php endif; ?>
  <?php if($extra['number_of_pages'] > 1): ?>
    <div class="row justify-content-md-center">
        <nav aria-label="Page navigation">
            <ul class="pagination">
            <? for ($page=1;$page<=$extra['number_of_pages'];$page++) : ?>
            <li class="page-item pt-5"><a class="page-link white-text primary-color-dark" href="<?php echo URLROOT .'/posts/mypics/'.$page?>" ><?php echo $page; ?></a></li>
            <?php endfor;?>
            </ul>
        </nav>
    </div>
<?php endif; ?>
</div>

<script>
var choiceArray = document.querySelectorAll('a[id=deletePost]');

for (var i = 0; i < choiceArray.length; i++) {
    choiceArray[i].addEventListener('click', function(e){
        var pic = this.firstElementChild.id;
        deletepic(pic);
    });
}


function deletepic(pic){
var form = new FormData;
form.append('pictureid',pic);
var xhr = new XMLHttpRequest();
xhr.open('POST', 'http://localhost/posts/deleteit');
xhr.onload = function (){
    if(this.status == 200){
        location.reload();
    }
}
xhr.send(form);
}
</script>
<?php require APPROOT . '/views/inc/footer.php'; ?>
