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

    $(document).on('click','#deleteProfileImage',function(){
      if (confirm('Are you sure you want to remove your Profile Image?')) {
          var admin_id = $(this).data('admin-id');
          $.ajax({
              headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              type:'post',
              url:'delete-profile-image',
              data:{admin_id:admin_id},
              success:function(resp){
                  if(resp['status']==true){
                      alert(resp['message']);
                      $('#profileImageBlock').remove();
                  }
              },
              error:function(){
                  alert("Error occurred while deleting the image.");
              }
          });
      }
  });

  // Update Subadmin Status
// Update Subadmin Status
$(document).on("click", ".updateSubadminStatus", function() {
  var status = $(this).find("i").data("status");
  var subadmin_id = $(this).data("subadmin_id");

  $.ajax({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      type: 'POST',
      url: '/admin/update-subadmin-status',
      data: { status: status, subadmin_id: subadmin_id },
      success: function(resp) {
          if (resp['status'] == 0) {
              $("a[data-subadmin_id='" + subadmin_id + "']").html(
                  "<i class='fas fa-toggle-off' style='color:grey' data-status='Inactive'></i>"
              );
          } else if (resp['status'] == 1) {
              $("a[data-subadmin_id='" + subadmin_id + "']").html(
                  "<i class='fas fa-toggle-on' style='color:#3f6ed3' data-status='Active'></i>"
              );
          }
      },
      error: function() {
          alert("Erreur lors de la mise à jour du statut.");
      }
  });
});



  });
