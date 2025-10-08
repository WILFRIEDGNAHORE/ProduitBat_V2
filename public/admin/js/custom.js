$(document).ready(function () {
    // Check Admin Password is correct or not
    $("#current_pwd").keyup(function () {
        var current_pwd = $("#current_pwd").val();
        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            url: "/admin/verify-password",
            type: "post",
            data: {
                current_pwd: current_pwd,
            },
            success: function (resp) {
                if (resp == "false") {
                    $("#verifyPwd").html(
                        "<font color='red'>Current Password is not correct</font>"
                    );
                } else {
                    $("#verifyPwd").html(
                        "<font color='green'>Current Password is correct</font>"
                    );
                }
            },
            error: function () {
                alert("Error");
            },
        });
    });

    $(document).on("click", "#deleteProfileImage", function () {
        if (confirm("Are you sure you want to remove your Profile Image?")) {
            var admin_id = $(this).data("admin-id");
            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                type: "post",
                url: "delete-profile-image",
                data: { admin_id: admin_id },
                success: function (resp) {
                    if (resp["status"] == true) {
                        alert(resp["message"]);
                        $("#profileImageBlock").remove();
                    }
                },
                error: function () {
                    alert("Error occurred while deleting the image.");
                },
            });
        }
    });

    // Update Subadmin Status
    // Update Subadmin Status
    $(document).on("click", ".updateSubadminStatus", function () {
        var status = $(this).find("i").data("status");
        var subadmin_id = $(this).data("subadmin_id");

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "POST",
            url: "/admin/update-subadmin-status",
            data: { status: status, subadmin_id: subadmin_id },
            success: function (resp) {
                if (resp["status"] == 0) {
                    $("a[data-subadmin_id='" + subadmin_id + "']").html(
                        "<i class='fas fa-toggle-off' style='color:grey' data-status='Inactive'></i>"
                    );
                } else if (resp["status"] == 1) {
                    $("a[data-subadmin_id='" + subadmin_id + "']").html(
                        "<i class='fas fa-toggle-on' style='color:#3f6ed3' data-status='Active'></i>"
                    );
                }
            },
            error: function () {
                alert("Erreur lors de la mise à jour du statut.");
            },
        });
    });
    // ✅ Update Category Status (AJAX)
    $(document).on("click", ".updateCategoryStatus", function () {
        var status = $(this).find("i").data("status");
        var category_id = $(this).data("category-id");

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "POST",
            url: "/admin/update-category-status",
            data: { status: status, category_id: category_id },
            success: function (resp) {
                if (resp.status == 0) {
                    $("a[data-category-id='" + category_id + "']").html(
                        "<i class='fas fa-toggle-off' style='color:grey' data-status='Inactive'></i>"
                    );
                } else if (resp.status == 1) {
                    $("a[data-category-id='" + category_id + "']").html(
                        "<i class='fas fa-toggle-on' style='color:#3f6ed3' data-status='Active'></i>"
                    );
                }
            },
            error: function () {
                alert("An error occurred while updating the category status.");
            },
        });
    });

    // ✅ Update Product Status (AJAX)
    $(document).on("click", ".updateProductStatus", function () {
        var status = $(this).find("i").data("status");
        var product_id = $(this).data("product-id");

        $.ajax({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            type: "POST",
            url: "/admin/update-product-status",
            data: { status: status, product_id: product_id },
            success: function (resp) {
                if (resp.status == 0) {
                    $("a[data-product-id='" + product_id + "']").html(
                        "<i class='fas fa-toggle-off' style='color:grey' data-status='Inactive'></i>"
                    );
                } else if (resp.status == 1) {
                    $("a[data-product-id='" + product_id + "']").html(
                        "<i class='fas fa-toggle-on' style='color:#3f6ed3' data-status='Active'></i>"
                    );
                }
            },
            error: function () {
                alert("An error occurred while updating the product status.");
            },
        });
    });

    $(document).on("click", "#deleteCategoryImage", function () {
        if (confirm("Are you sure you want to remove this Category Image?")) {
            var category_id = $(this).data("category-id");

            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                type: "POST",
                url: "/admin/delete-category-image",
                data: { category_id: category_id },
                success: function (resp) {
                    if (resp.status === true) {
                        alert(resp.message);
                        $("#categoryImageBlock").remove(); // ✅ Supprime le bloc d'image
                    } else {
                        alert("Failed to delete the image.");
                    }
                },
                error: function () {
                    alert("Error occurred while deleting the image.");
                },
            });
        }
    });

    $(document).on("click", "#deleteSizeChart", function () {
        if (confirm("Are you sure you want to remove this Size Chart?")) {
            var category_id = $(this).data("category-id");

            $.ajax({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                type: "POST",
                url: "/admin/delete-size-chart",
                data: { category_id: category_id },
                success: function (resp) {
                    if (resp.status === true) {
                        alert(resp.message);
                        $("#sizeChartBlock").remove(); // ✅ Supprime le bloc d'image
                    } else {
                        alert("Failed to delete the image.");
                    }
                },
                error: function () {
                    alert("Error occurred while deleting the image.");
                },
            });
        }
    });

    $(document).on("click", ".confirmDelete", function (e) {
        e.preventDefault();
        let button = $(this);
        let module = button.data("module");
        let moduleid = button.data("id");
        let form = button.closest("form");
        let redirectUrl = "/admin/delete-" + module + "/" + moduleid;

        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                if (form.length > 0) {
                    form.submit(); // Submit form (used in category module)
                } else {
                    window.location.href = redirectUrl; // Redirect for subadmin delete
                }
            }
        });
    });
});
