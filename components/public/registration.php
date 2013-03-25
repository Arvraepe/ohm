<script type="text/javascript">
  $(document).ready(function(){

    $("#pUsername").blur(function () { 
      checkUsernameValidation({ vid: "pUsername", ok: "Available", nok: "Unavailable" }, check); 
    }); 

    $("#pEmail").blur(function () { 
      checkEmailValidation({ vid: "pEmail", ok: "Ok", nok: "Incorrect" }, check); 
    }); 

    $("#pPassword").blur(function () { 
      checkPasswordValidation({ vid: "pPassword", ok: "Ok", nok: "Bad password" }, check); 
    }); 

    $("#pPassword2").blur(function () { 
      checkPassword2Validation({ vid: "pPassword2", ok: "Ok", nok: "Passwords don't match" }, check); 
    }); 

    $("#pSubmit").click(function(){
      var values = [];
        
      checkEmailValidation(values, finalCheck);
      checkPasswordValidation(values, finalCheck);
      checkPassword2Validation(values, finalCheck);
      checkUsernameValidation(values, function(){   
        
        // Check all the values in the array
        var valid = true;
        for (var i = values.length - 1; i >= 0; i--) {
          if(!values[i])
            valid = false;
        };

        // If the array contained a false or if the checkbox isn't checked
        if( !valid || !$('#pAgreement').is(':checked')){
          $("#pRegistrationError").fadeIn(200);
          return false;
        } else {
          // Form passed
          $("#pRegistrationForm").submit();
        }
      });

      return false;

    });

  });

  // Generic Check Callback
  function check(obj, validation) {
    if (validation){
      $("#"+obj.vid+"Val").removeClass("label-important").addClass("label-success");
      $("#"+obj.vid+"Val").text(obj.ok);
    } else {
      $("#"+obj.vid+"Val").removeClass("label-success").addClass("label-important");
      $("#"+obj.vid+"Val").text(obj.nok);
    }
  }

  // Pushes the true or false into an array... this array can be checked before sending info
  function finalCheck(obj, validation){
    obj.push(validation);
  }

  // Validation checks... call the callback functions 
  function checkPasswordValidation(obj, callback) {
    var password = $("#pPassword").val();
    callback(obj, password.length >= 5);
  }

  function checkPassword2Validation(obj, callback) {
    var password = $("#pPassword").val();
    var password2 = $("#pPassword2").val();
    
    if(password2.length < 5) obj.nok = "Bad password";
    callback(obj, password === password2 && password2.length >= 5);
  }

  function checkEmailValidation(obj, callback) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    callback(obj, re.test( $("#pEmail").val() ));
  }

  function checkUsernameValidation(obj, callback){
    $.ajax({
      type: "POST",
      url: "scripts/scr_checkusername.php",
      data: { username: $("#pUsername").val() }
    }).done(function(msg){ 
      if (msg == "true"){
        callback(obj, true);
      } else {
        callback(obj, false);
      }
    });  
  }

</script>
<h1>Registration</h1>
<form class="form-horizontal well" id="pRegistrationForm" method="POST" action="scripts/scr_cr_user.php">

  <div id="pRegistrationError" <?php if(!isset($_GET['e'])) echo('style="display: none;"'); ?>class="alert alert-error">Your info did <strong>not</strong> pass the validation. Have you read and accepted the <strong>terms of agreement?</strong></div>

  <div class="control-group">
    <label class="control-label" for="pUsername">Username</label>
    <div class="controls">
      <input type="text" name="pUsername" id="pUsername" placeholder="Username" value="myusername"> 
      <span class="label" id="pUsernameVal"></span> <small>(min 4 characters)</small>
    </div>
  </div>

  <div class="control-group">
    <label class="control-label" for="pEmail">Email</label>
    <div class="controls">
      <input type="text" name="pEmail" id="pEmail" placeholder="Email" value="arvraepe@gmail.com">
      <span class="label" id="pEmailVal"></span> <small>(used for activation)</small>
    </div>
  </div>

  <div class="control-group">
    <label class="control-label" for="pPassword">Password</label>
    <div class="controls">
      <input type="password" name="pPassword" id="pPassword" placeholder="Password" value="test1"> 
      <span class="label" id="pPasswordVal"></span> <small>(min 5 characters)</small>
    </div>
  </div>

  <div class="control-group">
    <label class="control-label" for="pPassword2">Password</label>
    <div class="controls">
      <input type="password" name="pPassword2" id="pPassword2" placeholder="Re-type Password" value="test1">
      <span class="label" id="pPassword2Val"></span>
    </div>
  </div>

  <div class="control-group">
    <div class="controls">
      <label class="checkbox">
        <input id="pAgreement" checked type="checkbox" name="pAgreement" value="agreed"> I have <strong>read</strong> and <strong>accept</strong> 
        the <a class="btn btn-small btn-primary" href="#">terms of agreement</a>
      </label>
    </div>
  </div>

  <div class="control-group">
    <div class="controls">
      <button type="submit" id="pSubmit" class="btn">Register</button>
    </div>
  </div>

</form>