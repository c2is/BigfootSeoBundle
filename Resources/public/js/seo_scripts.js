var collectionHolder = $('ul.parameters');

var $addParameterLink = $('<a href="#" class="btn">Ajouter un paramètre</a>');
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

    // Initiate the available parameters
    $selectRoute = $('select.seo-metadata-route-choice');
    displayAvailableParameters($selectRoute.val());

    // OnChange Event on the route_id select (new Metadata)
    $selectRoute.change(function() {
        displayAvailableParameters($(this).val());
    });
});

function displayAvailableParameters(value) {
    var $container = $('#list_parameters');
    var $form = $container.closest('form');

    $.ajax({
        url: $container.data('url')+'/'+value,
        type: 'GET',
        cache: false,
        success: function(data) {
            $('#list_parameters').html(data);
        }
    });
}


function addParameterForm(collectionHolder, $newLinkLi) {
    var prototype = collectionHolder.attr('data-prototype');

    var newForm = prototype.replace(/__name__/g, collectionHolder.children().length);

    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);

    addParameterFormDeleteLink($newFormLi);
}

function addParameterFormDeleteLink($parameterFormLi) {

    var $removeFormA = $('<a href="#" class="btn">Supprimer ce paramètre</a>');
    $parameterFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        e.preventDefault();

        $parameterFormLi.remove();
    });
}