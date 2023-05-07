import './bootstrap';
$(document).ready(function () {
    setNullProjectIds();
});
  

$(".edit-project-btn").on("click", function () {
    $('#edit-id').val($(this).data('id'));
    $('#edit-name').val($(this).data('name'));
    $('#edit-description').val($(this).data('description'));
    $('#edit-start-date').val($(this).data('start-date'));
    $('#edit-due-date').val($(this).data('due-date'));
    $('#edit-status').val($(this).data('status'));
});

$(".edit-task-btn").on("click", function () {
    $('#edit-id').val($(this).data('id'));
    $('#edit-name').val($(this).data('name'));
    $('#edit-project-id').val($(this).data('project-id'));
    $('#edit-start-date').val($(this).data('start-date'));
    $('#edit-due-date').val($(this).data('due-date'));
    $('#edit-status').val($(this).data('status'));
});

$(".edit-timesheet-btn").on("click", function () {
    $('#edit-id').val($(this).data('id'));
    $('#edit-date').val($(this).data('date'));
    $('#edit-project-id').val($(this).data('project-id'));
    updateEditTasks();
    $('#edit-task-id').val($(this).data('task-id'));
    $('#edit-description').val($(this).data('description'));
    $('#edit-hour').val($(this).data('hour'));
});


$(".check-all").on("change", function() {
    if ($(this).is(':checked')) {
        $('.check-this').prop('checked', true);
    } else {
        $('.check-this').prop('checked', false);
    }
    updateIds()
});

$(".check-this").on("change", function() {
    var allChecked = $('.check-this').length === $('.check-this:checked').length;
    $('.check-all').prop('checked', allChecked);
    updateIds()
});

function updateIds() {
    var checkedIds = [];
    $('.check-this:checked').each(function() {
        checkedIds.push($(this).data('id'));
    });
    console.log(checkedIds)
    $('#delete-ids').val(JSON.stringify(checkedIds));
}

function setNullProjectIds() {
    $('#delete-ids').val('');
}

// $("#delete-record-btn").on("click",function() {
//     if ($('#delete-ids').val() != '') {
//     $('#delete-record-form').submit();
//     } else {
//         $('#noSelectedRecordModal').fadeIn();
//     }
// });

$("#delete-record-btn").on("click",function() {
    if ($('#delete-ids').val() != '') {
        $('#confirmDeleteModal').fadeIn();
    } else {
        $('#noSelectedRecordModal').fadeIn();
    }
});

$(".close-no-select-btn").on("click",function() {
    $('#noSelectedRecordModal').fadeOut();
})

$(".confirm-delete-btn").on("click",function() {
    $('#delete-record-form').submit();
});

$(".close-confirm-delete-btn").on("click",function() {
    $('#confirmDeleteModal').fadeOut();
})

