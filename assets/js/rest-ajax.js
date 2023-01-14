jQuery(document).ready(function ($) {
  console.log(sovObj);
  // delete event
  $(".mylisting-wrapper").on("click", ".delete-btn", deleteListing);

  // edit event
  $(".mylisting-wrapper").on("click", ".edit-btn", editListing);

  // update event
  $(".mylisting-wrapper").on("click", ".update-btn", updateListing);

  // submit event
  $(".new-listing-submit").on("click", createtListing);

  // All Methods will go here

  //create method
  function createtListing(e) {
    // Get file filed value
    var fileInputVal = $(".post-thumb").val();
    var title = $(".new-listing-title").val();
    var content = $(".new-listing-body").val();

    //validate title
    if (!title.length) {
      alert("Please enter a title!");
      return;
    }

    //validate content
    if (!content.length) {
      alert("Please enter a content!");
      return;
    }
    //validate image field
    if (!fileInputVal.length) {
      alert("Please attach an image for your service");
      return;
    }
    // Featured Image obj
    var featuredImage = {
      file: $(".post-thumb")[0].files[0],
    };
    // Lets make an Ajax call to add image on the media
    $.ajax({
      beforeSend: function (xhr) {
        xhr.setRequestHeader("X-WP-Nonce", sovObj.restNonce);
      },
      headers: {
        "Content-Type": "image/jpeg",
        "Content-Disposition": `attachment; filename=${featuredImage.file.name}`,
      },
      processData: false,
      contentType: false,
      url: sovObj.restURL + "wp/v2/media",
      type: "POST",
      data: featuredImage.file,
      success: function (response) {
        // prepare the data obj
        var createPostObj = {
          title,
          content,
          status: "publish",
          featured_media: response.id,
        };
        var featuredImageUrl = response.guid.raw;

        // Empty the upload filed once submit done
        $(".post-thumb").val("");

        //Once success, call another request for posting other data
        $.ajax({
          beforeSend: function (xhr) {
            xhr.setRequestHeader("X-WP-Nonce", sovObj.restNonce);
          },
          url: sovObj.restURL + "wp/v2/sov_dirlist/",
          type: "POST",
          data: createPostObj,
          success: function (response) {
            $(".new-listing-title, .new-listing-body").val("");
            $(`
              <li data-id="${response.id}" class="item">
                <div class="thumb">
                <img src=${featuredImageUrl} />
                
                </div>
                <div class="contents">
                    <input readonly class="listing-title-field" type="text" value="${response.title.raw}" />

                    <textarea class="listing-body-field" readonly name="" id="" cols="30" rows="10">${response.content.raw}</textarea>
                    <button class="update-btn">Save</button>
                </div>
                <div class="actions">
                    <button class="edit-btn">Edit</button>
                    <button class="delete-btn">Delete</button>
                </div>
              </li>
            `)
              .prependTo(".mylisting-wrapper")
              .hide()
              .slideDown();
            console.log("Congrats Akramul, Post updated");
            console.log(response);
          },
          error: function (response) {
            console.log("Sorry");
            console.log(response);
          },
        });
        console.log("image upload success");
        console.log(response);
      },
      error: function (response) {
        console.log("image upload failed");
        console.log(response);
      },
    });
  }
  function updateListing(e) {
    // get the parent li
    var thisListing = $(e.target).parents("li");

    // prepare the data obj

    var updatedPost = {
      title: thisListing.find(".listing-title-field").val(),
      content: thisListing.find(".listing-body-field").val(),
    };

    // Lets make an Ajax call
    $.ajax({
      beforeSend: function (xhr) {
        xhr.setRequestHeader("X-WP-Nonce", sovObj.restNonce);
      },
      url: sovObj.restURL + "wp/v2/sov_dirlist/" + thisListing.data("id"),
      type: "POST",
      data: updatedPost,
      success: function (response) {
        thisListing
          .find(".listing-title-field, .listing-body-field")
          .attr("readonly", "readonly")
          .removeClass("active-field");
        thisListing.find(".update-btn").removeClass("update-btn-visible");
        console.log("Congrats Akramul, Post updated");
        console.log(response);
      },
      error: function (response) {
        console.log("Sorry");
        console.log(response);
      },
    });
  }

  //Edit method
  function editListing(e) {
    // get the parent li
    var thisListing = $(e.target).parents("li");
    // find the input and textarea field, remove readonly, add a new class
    thisListing
      .find(".listing-title-field, .listing-body-field")
      .removeAttr("readonly")
      .addClass("active-field");
    // add class to make visible the 'Save' button when clicked 'edit' button
    thisListing.find(".update-btn").addClass("update-btn-visible");
  }

  // delete method
  function deleteListing(e) {
    // get the parent li
    var thisListing = $(e.target).parents("li");

    // Lets make an Ajax call
    $.ajax({
      beforeSend: function (xhr) {
        xhr.setRequestHeader("X-WP-Nonce", sovObj.restNonce);
      },
      url: sovObj.restURL + "wp/v2/sov_dirlist/" + thisListing.data("id"),
      type: "DELETE",
      success: function (response) {
        thisListing.slideUp();
        console.log("Congrats Akramul, you did your first Ajax call ever");
        console.log(response);
      },
      error: function (response) {
        console.log("Sorry");
        console.log(response);
      },
    });
  }
});
