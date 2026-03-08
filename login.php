<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include('./db_connect.php');
?>
<?php 
if(isset($_SESSION['login_id']))
header("location:index.php?page=accueil");

?>
<?php include 'header.php' ?>
<!-- L'ajout de l'image en arrière plan et ajustement de la taille de l'image -->
<body class="hold-transition login-page" style="background-image: url('assets/uploads/5137775.jpg'); background-size: contain; background-position: center; background-repeat: no-repeat;">
<div class="login-box">

<!--Gestion du texte qui s'affiche à la page de connexion  -->
<div class="login-logo">
            <a href="#">
                <b>WEST Ingénierie Projects Management System</b>
                <br>
                <span>Veuillez vous connecter s'il vous plaît !</span>
            </a>
        </div>

  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <form action="" id="login-form">
        <div class="input-group mb-3">
          <input type="email" class="form-control" name="email" required placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" required placeholder="Mot de passe">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!-- Pour ajuster la largeur du button contenant se souvenir, on change la valeur devant col- -->
          <div class="col-7">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
              Se souvenir
              </label>
            </div>
          </div>
          <!--Pour ajuster la largeur du button contenant se connecter, on change la valeur devant col- --> 
          <div class="col-5">
            <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->
<script>
  $(document).ready(function(){
    $('#login-form').submit(function(e){
    e.preventDefault()
    start_load()
    if($(this).find('.alert-danger').length > 0 )
      $(this).find('.alert-danger').remove();
    $.ajax({
      url:'ajax.php?action=login',
      method:'POST',
      data:$(this).serialize(),
      error:err=>{
        console.log(err)
        end_load();

      },
      success:function(resp){
        if(resp == 1){
          location.href ='index.php?page=accueil';
        }else{
          $('#login-form').prepend('<div class="alert alert-danger">Nom ou mot de passe incorrecte.</div>')
          end_load();
        }
      }
    })
  })
  })
</script>
<?php include 'footer.php' ?>

</body>
</html>
