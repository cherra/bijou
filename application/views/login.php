<style type="text/css">

  .form-signin {
    padding-top: 40px;
    padding-bottom: 40px;
    max-width: 350px;
    padding: 40px 29px 40px;
    margin: 50px auto 20px;
    background-color: #fff;
    border: 1px solid #e5e5e5;
    -webkit-border-radius: 5px;
       -moz-border-radius: 5px;
            border-radius: 5px;
    -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
       -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
            box-shadow: 0 1px 2px rgba(0,0,0,.05);
  }

  .form-signin .form-signin-heading,
  .form-signin .checkbox {
    margin-bottom: 10px;
  }

  .form-signin input[type="text"],
  .form-signin input[type="password"] {
    font-size: 16px;
    height: auto;
    margin-bottom: 15px;
    padding: 7px 9px;
  }

</style>
<div class="container">
    <div class="row">
        <div>
            <?php echo form_open('login/process', array('class' => 'form-signin')); ?>
              <h2 class="form-signin-heading">Ingreso</h2>
              <input type="text" name="username" class="form-control" placeholder="Usuario" autofocus>
              <input type="password" name="password" class="form-control" placeholder="Contraseña">
              <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
            <?php echo form_close(); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-offset-4 col-lg-4">
            <p><?php if(isset($msg)) echo $msg; ?></p>
        </div>
    </div>
</div> <!-- /container -->

<script> 
    $(document).ready(function(){
        $('#usuario').focus();
    });
    
</script>
