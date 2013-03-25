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
    callback(obj, (password.length >= 5));
  }

  function checkPassword2Validation(obj, callback) {
    var password = $("#pPassword").val();
    var password2 = $("#pPassword2").val();
    callback(obj, password === password2);
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
<form class="form-horizontal well">

  <div class="control-group">
    <label class="control-label" for="pUsername">Username</label>
    <div class="controls">
      <input type="text" id="pUsername" placeholder="Username"> 
      <span class="label" id="pUsernameVal"></span> <small>(min 4 characters)</small>
    </div>
  </div>

  <div class="control-group">
    <label class="control-label" for="pEmail">Email</label>
    <div class="controls">
      <input type="text" id="pEmail" placeholder="Email">
      <span class="label" id="pEmailVal"></span> <small>(used for activation)</small>
    </div>
  </div>

  <div class="control-group">
    <label class="control-label" for="pPassword">Password</label>
    <div class="controls">
      <input type="password" id="pPassword" placeholder="Password"> 
      <span class="label" id="pPasswordVal"></span> <small>(min 5 characters)</small>
    </div>
  </div>

  <div class="control-group">
    <label class="control-label" for="pPassword2">Password</label>
    <div class="controls">
      <input type="password" id="pPassword2" placeholder="Re-type Password">
      <span class="label" id="pPassword2Val"></span>
    </div>
  </div>

  <div class="control-group">
    <div class="controls">
      <button type="submit" class="btn">Sign in</button>
    </div>
  </div>

</form>