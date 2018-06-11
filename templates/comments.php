<?php
/**
 * This template is used to display comments.
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

$postId = sanitize_text_field($_GET['postId']);
AnyComment()->setCurrentPost($postId);

if (post_password_required($postId) || !comments_open($postId)) {
    return;
}

$classPrefix = AnyComment()->classPrefix();
?>

<link rel="stylesheet" href="<?= AnyComment()->plugin_url() ?>/assets/css/comments.css?v=<?= AnyComment()->version ?>">
<link href="https://fonts.googleapis.com/css?family=Noto+Sans:400,700&amp;subset=cyrillic" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/iframe-resizer/3.6.1/iframeResizer.contentWindow.min.js"></script>
<script src="<?= AnyComment()->plugin_url() ?>/assets/js/timeago.min.js?v=<?= AnyComment()->version ?>"></script>

<div id="<?= $classPrefix ?>comments" data-origin-limit="<?= AC_Render::LIMIT ?>"
     data-current-limit="<?= AC_Render::LIMIT ?>"
     data-sort="<?= AC_Render::SORT_NEW ?>"
     data-guest="<?= !is_user_logged_in() ? "1" : "0" ?>">
    <?php do_action('anycomment_send_comment') ?>
    <?php do_action('anycomment_notifications') ?>
    <?php do_action('anycomment_load_comments') ?>
    <?php do_action('anycomment_footer') ?>
</div>

<script>
    // Load generic comments template
    function loadComments(options = {}) {
        showLoader();


        let limit = null,
            sort = null;

        console.log(options);

        if (options !== {}) {
            limit = 'limit' in options ? options.limit : null;
            sort = 'sort' in options ? options.sort : null;
        }

        jQuery.post('<?= AnyComment()->ajax_url() ?>', {
            action: 'render_comments',
            _wpnonce: '<?= wp_create_nonce("load-comments-nonce") ?>',
            postId: '<?= $postId ?>',
            limit: limit,
            sort: sort
        }).done(function (data) {
            jQuery('#<?= $classPrefix ?>load-container').html(data);
            loadTime();
        }).fail(function () {
            console.log('Unable to get most recent comments');
        }).always(function () {
            hideLoader();
        });
    }

    // Load next comments if available
    function loadNext() {
        let root = getRoot();
        let newLimit = parseInt(root.attr('data-current-limit')) + 10;

        root.attr('data-current-limit', newLimit);

        loadComments({limit: newLimit});
    }

    // Get root object
    function getRoot() {
        return jQuery('#<?= $classPrefix ?>comments') || '';
    }

    function getForm() {
        return jQuery('#send-comment-form') || '';
    }


    function getCommentField() {
        let form = getForm();

        if (!form) {
            return;
        }

        return form.find('[name="comment"]') || '';
    }

    function commentSort(type) {
        loadComments({sort: type});
    }

    function checkAuthorization() {
        let root = getRoot();
        let isGuest = root.data('guest');
        let guestOverlay = jQuery('#auth-required');

        if (isGuest) {
            guestOverlay.hide();
            guestOverlay.fadeIn(300, function () {
                jQuery(this).hide();
                jQuery(this).show();
            });

            return true;
        }

        return false;
    }

    // Reply to some comment
    function replyComment(el, replyTo, replyToName) {

        if (!replyTo) {
            return;
        }

        let form = getForm();


        if (!form) {
            return;
        }


        if (checkAuthorization()) {
            return;
        }

        let commentField = form.find('[name="comment"]') || '';
        let replyToField = form.find('[name="reply_to"]') || '';


        if (!commentField || !replyToField) {
            return;
        }

        if (replyToName) {
            let replyToPlaceholderText = commentField.data('reply-name').replace('{name}', replyToName);
            commentField.attr('placeholder', replyToPlaceholderText);
        }

        replyToField.val(replyTo);
        commentField.focus();

        return false;
    }

    // Genetic send comment function
    function sendComment(el, formId) {

        let form = jQuery(formId) || '';

        if (!form) {
            return;
        }

        let commentField = form.find('[name="comment"]') || '';
        let postIdField = form.find('[name="post_id"]') || '';
        let actionField = form.find('[name="action"]') || '';
        let nonceField = form.find('[name="nonce"]') || '';
        let replyToField = form.find('[name="reply_to"]') || '';
        let commentCountEl = jQuery('#comment-count') || '';

        if (!commentField || !postIdField || !actionField || !nonceField) {
            return;
        }

        let commentText = commentField.val().trim() || '';

        if (!commentText) {
            return null;
        }

        showLoader();

        let data = form.serialize();

        jQuery.post('<?= AnyComment()->ajax_url() ?>', data, function (data) {
            if (data.success) {
                let response = JSON.parse(data.response);
                if (commentCountEl) {
                    commentCountEl.text(response.comment_count_text);
                }

                replyToField.val('');
                commentField.val('');
                commentField.attr('placeholder', commentField.data('original-placeholder'));
                loadComments();
            } else {
                addError(data.response.error);
                hideLoader();
            }
            commentField.focus();
        }, 'json');

        return false;
    }

    /**
     * Display loader.
     */
    function showLoader() {
        let loader = getLoader();
        if (!loader) {
            return;
        }

        if (!loader.length) {
            return;
        }

        let loaderHtml = loader.show();
    }

    /**
     * Hide loader.
     */
    function hideLoader() {
        let loader = getLoader();
        if (!loader) {
            return;
        }

        if (!loader.length) {
            return;
        }

        let loaderHtml = loader.hide();
    }

    /**
     * Get loader.
     */
    function getLoader() {
        return jQuery('#<?= AnyComment()->classPrefix()?>loader');
    }

    /**
     * Add error alert.
     * @param message Message of the alert.
     */
    function addError(message) {
        addAlert('error', message);
    }

    /**
     * Add success alert.
     * @param message Message of the alert.
     */
    function addSuccess(message) {
        addAlert('success', message);
    }

    function addAlert(type, message) {
        if (!type || !message || (type !== 'success' && type !== 'error')) {
            return;
        }

        let alert = '<p class="{class}">{text}</p>'
            .replace('{class}', '<?= $classPrefix ?>-notification-' + type)
            .replace('{text}', message);
        let notifications = jQuery('#<?= $classPrefix ?>-notifications');
        notifications.html(alert);
        notifications.slideDown(300);
    }

    loadComments();

    // Load time
    function loadTime() {
        let i = setInterval(function () {
            if (('timeago' in window)) {
                timeago().render(jQuery('.timeago-date-time'));
                //{defaultLocale: '<?= get_locale() ?>'}
                clearInterval(i);
            }
        }, 1000);
    }
</script>