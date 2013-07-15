var collectionHolder = $('ul.parameters');

var $addParameterLink = $('<a href="#" class="add_parameter_link">Ajouter un paramètre</a>');
var $newLinkLi = $('<li></li>').append($addParameterLink);

jQuery(document).ready(function() {

    collectionHolder.find('li').each(function() {
        addParameterFormDeleteLink($(this));
    });

    collectionHolder.append($newLinkLi);

    $addParameterLink.on('click', function(e) {
        e.preventDefault();
        addParameterForm(collectionHolder, $newLinkLi);
    });


    // OnChange Event on the route_id select (new Metadata)
    $('#metadata_route').change(function() {

        var currentForm = $(this).parent().parent('form');

        $.ajax({
            url: currentForm.attr('action'),
            type: currentForm.attr('method'),
            data: { route_name : $(this).val() },
            cache: false,
            success: function(data) {
                $('#list_parameters').html(data);
            }
        });
    });
});


function addParameterForm(collectionHolder, $newLinkLi) {
    var prototype = collectionHolder.attr('data-prototype');

    var newForm = prototype.replace(/__name__/g, collectionHolder.children().length);

    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);

    addParameterFormDeleteLink($newFormLi);
}

function addParameterFormDeleteLink($parameterFormLi) {

    var $removeFormA = $('<a href="#">Supprimer ce paramètre</a>');
    $parameterFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        e.preventDefault();

        $parameterFormLi.remove();
    });
}