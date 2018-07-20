<?php
/**
 * Template is used to render send comment box.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$post = AnyComment()->getCurrentPost();
?>

<div id="anycomment-send-comment" class="send-comment <?= ( is_user_logged_in() ? 'send-comment-authorized' : '' ) ?>">
    <div class="send-comment-supheader">
        <div class="send-comment-supheader__count"
             id="comment-count"><?php do_action( 'anycomment_get_comment_count_text', $post->ID ) ?></div>
        <div class="send-comment-supheader__dropdown">
            <div class="send-comment-supheader__dropdown-header" onclick="return jQuery('#sort-dropdown').toggle();">
				<?= __( 'Sort By', 'anycomment' ) ?>
            </div>
            <div class="send-comment-supheader__dropdown-list" style="display: none" id="sort-dropdown">
                <ul>
                    <li onclick="return handleCommentSort('<?= AnyCommentRender::SORT_NEW ?>')"><?= __( 'Newest', 'anycomment' ) ?></li>
                    <li onclick="return handleCommentSort('<?= AnyCommentRender::SORT_OLD ?>')"><?= __( 'Oldest', 'anycomment' ) ?></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="send-comment-body">
        <form method="POST" id="process-comment-form"
              onsubmit="processComment(this, '#process-comment-form'); return false;">
            <div class="send-comment-body-outliner">
				<?php if ( is_user_logged_in() ): ?>
					<?php if ( ( $avatarUrl = AnyComment()->auth->get_active_user_avatar_url() ) !== null ): ?>
                        <div class="send-comment-body-outliner__avatar"
                             style="background-image:url('<?= $avatarUrl ?>');"></div>
					<?php endif; ?>
				<?php else: ?>
                    <div class="send-comment-body-outliner__logo"></div>
				<?php endif; ?>
                <textarea name="content" required="required" class="send-comment-body-outliner__textfield"
                          placeholder="<?= __( "Add comment...", "anycomment" ) ?>"
                          data-original-placeholder="<?= __( "Add comment...", "anycomment" ) ?>"
                          data-reply-name="<?= __( "Reply to {name}...", "anycomment" ) ?>"
                          onclick="return shouldLogin()" <?= ( ! is_user_logged_in() ? "readonly" : "" ) ?>></textarea>

				<?php if ( ! is_user_logged_in() ): ?>
                    <div class="send-comment-body-outliner__auth" id="auth-required" style="display: none;">
                        <ul>
                            <li class="send-comment-body-outliner__auth-header"><?= __( 'Quick Login', 'anycomment' ) ?></li>
							<?php do_action( 'anycomment_login_with', get_permalink( AnyComment()->getCurrentPost()->ID ) ) ?>
                        </ul>
                    </div>
				<?php endif; ?>
            </div>

			<?php if ( is_user_logged_in() ): ?>
                <button class="btn send-comment-body__btn"><?= __( 'Send', "anycomment" ) ?></button>
                <input type="hidden" name="parent" value="0">
                <input type="hidden" name="edit_id">
                <input type="hidden" name="post" value="<?= $post->ID ?>">
                <input type="hidden" name="nonce" value="<?= wp_create_nonce( "add-comment-nonce" ) ?>">
			<?php endif; ?>
        </form>

        <div class="clearfix"></div>
    </div>
</div>