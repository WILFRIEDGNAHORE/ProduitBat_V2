  $(document).ready(function() {
    // Check Admin Password is correct or not
    $("#current_pwd").keyup(function() {
      var current_pwd = $("#current_pwd").val();
      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/admin/verify-password',
        type: "post",
        data: {
          current_pwd:current_pwd
        },
        success: function(resp) {
          if(resp == "false") {
            $("#verifyPwd").html("<font color='red'>Current Password is not correct</font>");
          }
          else {
            $("#verifyPwd").html("<font color='green'>Current Password is correct</font>");
          }
        },
        error:function() {
        alert("Error");        
    }
      });
    });
  });