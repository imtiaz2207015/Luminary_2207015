/**
 * capsule-engagement.js
 *
 * Handles the unified Reactions + Comments card on the capsule show page.
 * - Reaction button opens an emoji picker; picking one sends an AJAX
 *   request to reactions.toggle and updates the button + summary count
 *   without a page reload.
 * - Comment button toggles the inline comment list/input open and closed.
 * - New comments are submitted via AJAX and appended to the list live.
 * - Comment deletion is also AJAX-based and removes the item from the DOM.
 *
 * All requests are scoped to the capsule via the data-capsule-id attribute
 * on #engagement-card, and rely on routes already registered in web.php:
 * reactions.toggle, comments.store, comments.destroy.
 */

document.addEventListener('DOMContentLoaded', function () {
    const card = document.getElementById('engagement-card');
    if (!card) return; // locked capsules don't render this card

    const capsuleId = card.dataset.capsuleId;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
        || document.querySelector('input[name="_token"]')?.value;

    const reactionEmojis = { like: '👍', love: '💛', wow: '😮', sad: '😢', goals: '🔥' };

    const reactBtn = document.getElementById('react-btn');
    const reactBtnEmoji = document.getElementById('react-btn-emoji');
    const reactBtnLabel = document.getElementById('react-btn-label');
    const emojiPicker = document.getElementById('emoji-picker');
    const reactionSummary = document.getElementById('reaction-summary');

    const commentToggleBtn = document.getElementById('comment-toggle-btn');
    const commentsSection = document.getElementById('comments-section');
    const commentForm = document.getElementById('comment-form');
    const commentInput = document.getElementById('comment-input');
    const commentsList = document.getElementById('comments-list');
    const commentCountLabel = document.getElementById('comment-count-label');

    // ---- Reaction picker open/close ----
    reactBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        emojiPicker.classList.toggle('d-none');
    });

    document.addEventListener('click', function () {
        emojiPicker.classList.add('d-none');
    });

    emojiPicker.addEventListener('click', function (e) {
        e.stopPropagation(); // don't let the outside-click listener close it prematurely
    });

    // ---- Sending a reaction ----
    emojiPicker.querySelectorAll('.emoji-option').forEach(function (btn) {
        btn.addEventListener('click', async function () {
            const type = btn.dataset.type;
            emojiPicker.classList.add('d-none');

            try {
                const response = await fetch(`/capsules/${capsuleId}/react`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ type: type }),
                });

                if (!response.ok) throw new Error('Reaction request failed');

                const data = await response.json();

                if (data.action === 'removed') {
                    reactBtn.dataset.current = '';
                    reactBtnEmoji.textContent = reactionEmojis.like;
                    reactBtnLabel.textContent = 'React';
                } else {
                    reactBtn.dataset.current = type;
                    reactBtnEmoji.textContent = reactionEmojis[type];
                    reactBtnLabel.textContent = type.charAt(0).toUpperCase() + type.slice(1);
                }

                const total = Object.values(data.counts).reduce((sum, n) => sum + n, 0);
                reactionSummary.textContent = `${total} ${total === 1 ? 'reaction' : 'reactions'}`;
            } catch (err) {
                console.error('Could not update reaction:', err);
                alert('Could not update your reaction. Please try again.');
            }
        });
    });

    // ---- Comment section toggle ----
    commentToggleBtn.addEventListener('click', function () {
        commentsSection.classList.toggle('d-none');
        if (!commentsSection.classList.contains('d-none')) {
            commentInput.focus();
        }
    });

    // ---- Submitting a new comment ----
    commentForm.addEventListener('submit', async function (e) {
        e.preventDefault();
        const body = commentInput.value.trim();
        if (!body) return;

        try {
            const response = await fetch(`/capsules/${capsuleId}/comments`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ body: body }),
            });

            if (!response.ok) throw new Error('Comment request failed');

            const comment = await response.json();

            const noCommentsMsg = document.getElementById('no-comments-msg');
            if (noCommentsMsg) noCommentsMsg.remove();

            const commentEl = document.createElement('div');
            commentEl.className = 'd-flex gap-3 mb-3 comment-item';
            commentEl.dataset.commentId = comment.id;
            commentEl.innerHTML = `
                <img src="${comment.avatar_url}" style="width:36px; height:36px; border-radius:50%; flex-shrink:0;" alt="">
                <div style="flex:1;">
                    <div class="d-flex justify-content-between align-items-start">
                        <span style="color:#f5f0e8; font-weight:600; font-size:0.85rem;">${comment.user_name}</span>
                        <span style="color:#8b95a8; font-size:0.75rem;">${comment.created_at}</span>
                    </div>
                    <p style="color:#c8c0b8; font-size:0.88rem; margin:4px 0 0;">${comment.body}</p>
                    <button class="btn p-0 mt-1 delete-comment-btn" data-comment-id="${comment.id}" style="color:#8b95a8; font-size:0.75rem; background:none; border:none;">Delete</button>
                </div>
            `;
            commentsList.appendChild(commentEl);
            attachDeleteHandler(commentEl.querySelector('.delete-comment-btn'));

            commentInput.value = '';
            commentCountLabel.textContent = `${comment.count} comments`;
        } catch (err) {
            console.error('Could not post comment:', err);
            alert('Could not post your comment. Please try again.');
        }
    });

    // ---- Deleting a comment ----
    function attachDeleteHandler(btn) {
        btn.addEventListener('click', async function () {
            if (!confirm('Delete this comment?')) return;
            const commentId = btn.dataset.commentId;
            const commentEl = btn.closest('.comment-item');

            try {
                const response = await fetch(`/comments/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                });

                if (!response.ok) throw new Error('Delete request failed');

                commentEl.remove();
                const remaining = commentsList.querySelectorAll('.comment-item').length;
                commentCountLabel.textContent = `${remaining} comments`;

                if (remaining === 0) {
                    const empty = document.createElement('p');
                    empty.id = 'no-comments-msg';
                    empty.style.cssText = 'color:#8b95a8; font-size:0.85rem; text-align:center; padding:1rem 0;';
                    empty.textContent = 'No comments yet.';
                    commentsList.appendChild(empty);
                }
            } catch (err) {
                console.error('Could not delete comment:', err);
                alert('Could not delete this comment. Please try again.');
            }
        });
    }

    commentsList.querySelectorAll('.delete-comment-btn').forEach(attachDeleteHandler);
});