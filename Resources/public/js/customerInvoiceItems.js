/**
 * Created by juanma on 4/23/16.
 */



var $collectionHolder;
var $addTagLink = $(tagAAddOther);
jQuery(document).ready(function () {
    $collectionHolder = $('#contentMaterials');
    $collectionHolder.append($addTagLink);
    $collectionHolder.data('index', $collectionHolder.find(':input').length);
    $collectionHolder.find('.reminders').each(function () {
        addReminderFormDeleteLink($(this));
    });
    $addTagLink.on('click', function (e) {
        e.preventDefault();
        addReminderForm($collectionHolder, $addTagLink);
    });
});
function addReminderForm($collectionHolder, $newLink) {
    var prototype = $("#productRawMaterialTemplate");
    var index = $collectionHolder.data('index');

    prototype.find('.field').each(function () {
        var field = $(this);
        field.append(field.data('prototype'));
    });

    var newForm = prototype.html().replace(/__name__/g, index);
    newForm = $(newForm);
    $collectionHolder.data('index', index + 1);
    $newLink.before(newForm);
    addReminderFormDeleteLink(newForm);

    prototype.find('.field').each(function () {
        $(this).empty();
    });

}

function addReminderFormDeleteLink($tagFormLi) {
    var $removeFormA = $(tagARemove);
    $tagFormLi.find(".tools").append($removeFormA);
    $removeFormA.on('click', function (e) {
        e.preventDefault();
        $tagFormLi.remove();
    });
}