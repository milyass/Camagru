</div>
<script>
function navOption() {
 document.getElementById("navOption").classList.toggle("show");
}
</script>
<?php if(isset($_SESSION['darkmode']) && $_SESSION['darkmode'] == '1'):?>
<script>
    // darkmode
    cardbod = document.getElementsByClassName("card-body");
    for (var i = 0; i < cardbod.length; i++) {
        cardbod[i].style.backgroundColor="#2E2E2E";
    }
    cardfoot = document.getElementsByClassName("card-footer");
    for (var i = 0; i < cardfoot.length; i++) {
        cardfoot[i].style.backgroundColor="#212121";
    }
    var all = document.getElementsByTagName("*");
    for (var i=0; i < all.length; i++) {
        all[i].style.color = "white";
    }
    var bod = document.getElementsByTagName("body")[0];
        bod.style.backgroundColor = "black";

    var inputs = document.getElementsByTagName("input");
        for (var i=0; i < inputs.length; i++) {
        inputs[i].style.color = "black";
    }
    var uls = document.getElementsByTagName("ul");
        for (var i=0; i < uls.length; i++) {
        uls[i].style.color = "black";

    }
    var lis = document.getElementsByClassName("list-group-item");
        for(var i=0; i < lis.length; i++){
           lis[i].className += " list-group-item-dark";
        }

    var textareas = document.getElementsByTagName("textarea");
        for (var i=0; i < textareas.length; i++) {
            textareas[i].style.backgroundColor = "black";
    }
    
    var jumbotron = document.getElementsByClassName("jumbotron");
        for (var i=0; i <jumbotron.length; i++){
            jumbotron[i].style.backgroundColor="#212121";
    }
    var selects = document.getElementsByTagName("select");
        for (var i=0; i < selects.length; i++) {
            selects[i].style.backgroundColor = "black";
    }  

</script>
<?php endif; ?>
<!--  FOOTER  -->
<footer class="bg-dark">
<div class="text-center text-light py-3">© 2019 Copyright: milyass</div>
</footer>
<!-- Footer -->
</body>
</html>