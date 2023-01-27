jQuery(document).ready(function ($) {
  if (!$(".mylisting-wrapper li").length > 0) {
    $(".no-post").text("You have no item");
  }
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
          status: "private",
          featured_media: response.id,
        };
        var featuredImageUrl = response.guid.raw;
        var encodedPostObj = JSON.stringify(createPostObj);
        // Empty the upload filed once submit done
        $(".post-thumb").val("");

        //Once success, call another request for posting other data
        $.ajax({
          beforeSend: function (xhr) {
            xhr.setRequestHeader("X-WP-Nonce", sovObj.restNonce);
          },
          headers: {
            "Content-Type": "application/json",
          },
          url: sovObj.restURL + "sov-directory/v1/posts",
          type: "POST",
          data: encodedPostObj,
          success: function (response) {
            $(".new-listing-title, .new-listing-body").val("");
            $(`
              <li data-id="${response.ID}" class="item">
              
                <div class="thumb">
                <img src=${featuredImageUrl} />

                </div>
                <div class="contents">
                <span class="post-status">This post under review</span>
                    <input readonly class="listing-title-field" type="text" value="${response.post_title}" />

                    <textarea class="listing-body-field" readonly name="" id="" cols="30" rows="10">${response.post_content}</textarea>
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
            if ($(".mylisting-wrapper li").length > 0) {
              $(".no-post").text("");
            }
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

        if (!$(".mylisting-wrapper li").length > 0) {
          $(".no-post").text("You have no item");
        }
      },
      error: function (response) {
        console.log("Sorry");
        console.log(response);
      },
    });
  }

  // Ajax Pagination Event
  $(".pagination-wrapper").on("click", ".page-numbers", function (e) {
    e.preventDefault();
    var page = $(this).text() || 1;
    console.log(page);
    $.ajax({
      url: sovObj.restURL + "sov-directory/v1/posts/" + page,
      type: "GET",
      success: function (response) {
        let html = "";
        response.map(({ image, title }) => {
          html += `
            <div class="service inital">
              <img src="${image}" alt="">
              <h2>${title}</h2>
            </div>
          `;
        });
        $(".listing-wrapper").html(html);

        // Add some logic
        // make current paginate link unclickable
        //$('.unclickable-link').attr('href', 'javascript:void(0);');
        // $(document).on("click", "a.page-numbers", function () {
        //   $(this).attr("href", "javascript:void(0);");
        // });
        // $(document).ajaxSuccess(function () {
        //   setTimeout(function () {
        //     $("a.page-numbers").click(function () {
        //       $(this).addClass("hello");
        //     });
        //   }, 1000);
        // });

        // $(document).on("click", "a", function () {
        //   $(this).attr("target", "_blank");
        // });
        // setTimeout(function () {
        //   $("a.page-numbers").click(function () {
        //     $(this).addClass("hello");
        //   });
        // }, 1000);

        //Add pagination markup
        var totalPages = response[0].totalPages;
        var paginationMarkup = "";

        for (var i = 1; i <= totalPages; i++) {
          if (i == page) {
            paginationMarkup +=
              '<a href="javascript:void(0)" class="page-numbers active">' +
              i +
              "</a>";
          } else {
            paginationMarkup +=
              '<a href="#" class="page-numbers">' + i + "</a>";
          }
        }

        $(".pagination").html(paginationMarkup);

        console.log("Pagination endpoint success");
        console.log(response);

        // Handle the response data here
        // you can use jquery to append the new data in your post-listing div
      },
      error: function (error) {
        // Handle any errors here
        console.log("Pagination Failed");
      },
    });
  });
});
