"use strict";
var SearchHorizontal = {
    init: function () {
        var tagsInput, advancedSearchLink;
        tagsInput = document.querySelector("#kt_advanced_search_form").querySelector('[name="tags"]');
        new Tagify(tagsInput);
        advancedSearchLink = document.querySelector("#kt_horizontal_search_advanced_link");
        advancedSearchLink.addEventListener("click", function (event) {
            event.preventDefault();
            if (advancedSearchLink.innerHTML === "Advanced Search") {
                advancedSearchLink.innerHTML = "Hide Advanced Search";
            } else {
                advancedSearchLink.innerHTML = "Advanced Search";
            }
        });
    }
};

KTUtil.onDOMContentLoaded(function () {
    SearchHorizontal.init();
});

