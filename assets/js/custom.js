import $ from 'jquery';

$(function () {
    $(document).on('click', '.delete-button', function (e) {
        if (!confirm("Are you sure?")) {
            e.preventDefault();
        }
    });

    $(document).on('click', '.like-btn', function () {
        let dis = $(this);

        $.ajax({
            method: 'POST',
            url: '/like-blog',
            data: { id: dis.data('id') }
        }).done(function (data) {
            if (data.errors) {
                dis.parent().after("<div class='alert alert-danger like-blog-error'>" + data.errors + "</div>");
                dis.parent().parent().find('.like-blog-error').delay(3000).fadeOut();
            } else {
                dis.parent().find('.number-of-likes').html(data.likes);
                dis.toggleClass('btn-outline-danger btn-danger');
            }
        });
    });

    $(document).on('change', '#perPage, #orderBy, #itemType', function () {
        let dis = $(this);
        let type = dis.attr('id');
        let value = dis.find('option:selected').val();

        $('input[name=' + type + ']').val(value);
        $('form#advanced-search').trigger('submit');
    });

    $(document).on('click', '.delete-post', function (e) {
        e.preventDefault();

        if (!confirm("Are you sure?")) {
            return;
        }

        let dis = $(this);
        let id = dis.parent().data('id');

        $.ajax({
            method: 'POST',
            url: '/delete-comment',
            data: { id: id }
        }).done(function (data) {
            if (data.errors) {
                alert(data.errors)
            } else {
                dis.closest('.comment-wrapper').parent().remove();
            }
        });
    });

    $(document).on('click', '.edit-post', function (e) {
        e.preventDefault();

        let dis = $(this);
        let content = dis.closest('.comment-wrapper').find('.comment-content');
        let actualContent = content.html();
        let html = '<textarea rows="6" style="width: 100%;">' + actualContent + '</textarea>';
        dis.parent().prepend('<div class="d-none actual-content">' + actualContent + '</div>');
        dis.parent().prepend('<a href="#" class="cancel-edit">Cancel</a> <span class="edit-separator">|</span> <a href="#" class="update-post text-success">Update</a>');
        dis.hide();

        content.html(html);
    });

    $(document).on('click', '.cancel-edit', function (e) {
        e.preventDefault();

        let dis = $(this);
        let wrapper = dis.closest('.comment-wrapper');
        let oldContent = wrapper.find('.actual-content');

        wrapper.find('.edit-post').show();
        wrapper.find('.comment-content').html(oldContent.html());
        oldContent.remove();
        wrapper.find('.cancel-edit, .update-post, .edit-separator').remove();
    });

    $(document).on('click', '.update-post', function (e) {
        e.preventDefault();

        let dis = $(this);
        let id = dis.parent().data('id');
        let wrapper = dis.closest('.comment-wrapper');
        let content = wrapper.find('.comment-content');

        $.ajax({
            method: 'POST',
            url: '/update-comment',
            data: { id: id, content: content.find('textarea').val() }
        }).done(function (data) {
            if (data.errors) {
                alert(data.errors)
            } else {
                content.html(data.content);
                wrapper.find('.edit-post').show();
                wrapper.find('.cancel-edit, .update-post, .edit-separator').remove();
            }
        });
    });
});
