"use strict";
var ModalUserSearch = function () {
    var modalHandler, searchWrapper, suggestions, results, empty, processSearch, clearSearch;

    processSearch = function (event) {
        setTimeout(function () {
            var randomInt = KTUtil.getRandomInt(1, 3);
            suggestions.classList.add("d-none");
            if (randomInt === 3) {
                results.classList.add("d-none");
                empty.classList.remove("d-none");
            } else {
                results.classList.remove("d-none");
                empty.classList.add("d-none");
            }
            event.complete();
        }, 1500);
    };

    clearSearch = function () {
        suggestions.classList.remove("d-none");
        results.classList.add("d-none");
        empty.classList.add("d-none");
    };

    return {
        init: function () {
            modalHandler = document.querySelector("#kt_modal_users_search_handler");
            if (modalHandler) {
                searchWrapper = modalHandler.querySelector('[data-kt-search-element="wrapper"]');
                suggestions = modalHandler.querySelector('[data-kt-search-element="suggestions"]');
                results = modalHandler.querySelector('[data-kt-search-element="results"]');
                empty = modalHandler.querySelector('[data-kt-search-element="empty"]');
                var search = new KTSearch(modalHandler);
                search.on("kt.search.process", processSearch);
                search.on("kt.search.clear", clearSearch);
            }
        }
    };
}();

KTUtil.onDOMContentLoaded(function () {
    ModalUserSearch.init();
});
