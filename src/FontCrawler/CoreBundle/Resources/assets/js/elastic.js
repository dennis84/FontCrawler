$(function () {
    var searchResultContainer = $('#elastic-search-results');

    $('#elastic-search-field').on('keyup', function () {
        uri = Routing.generate('font_search_term', {
            'term': $(this).val()
        });

        $.get(uri, function(data) {
            searchResultContainer.html(data)
        });
    });
});
