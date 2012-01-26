
/*
 * This file is part of the fontcrawler package.
 *
 * (c) Dennis Dietrich <d.dietrich84@googlemail.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
