$(document).ready(function(){

    $("[data-toggle=tooltip]").tooltip();

    $('#search').focus();
    $('#search-clear').on('click', function(){ $('#search').val('').trigger('keyup').focus();} );
    
    $('#table-works').searchable({
        striped: true,
        oddRow: { 'background-color': '#f5f5f5' },
        evenRow: { 'background-color': '#fff' },
        searchType: 'fuzzy'
    });
});