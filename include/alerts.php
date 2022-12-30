<?php
if(isset($_SESSION['success'])){
    echo 
    '<div class="alert alert-success" role="alert">
        <strong>'.$_SESSION['success'].'</strong>
    </div>';
}
unset($_SESSION['success']);

if(isset($_SESSION['error'])){
    echo 
    '<div class="alert alert-danger" role="alert">
        <strong>'.$_SESSION['error'].'</strong>
    </div>';
}
unset($_SESSION['error']);
?>
<script>
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove(); 
        });
    }, 4000);
</script>