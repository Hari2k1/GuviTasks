jQuery("#p_form").on("submit", function (e) {
    jQuery("#save").val("Please wait...");
    jQuery("#save").attr("disabled", true);
    jQuery.ajax({
      url: "get_data.php",
      type: "post",
      data: jQuery("#p_form").serialize(),
      success: function (result) {
        jQuery("#save").val("Submit Details");
        jQuery("#save").attr("disabled", false);
        alert(result);
      },
    });

    e.preventDefault(); //prevents reloading of the page
  });