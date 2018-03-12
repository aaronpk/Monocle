          <div class="actions" data-url="<?= e($entry['url'] ?? '') ?>">
            <div class="action-buttons">

              <div class="dropdown">
                <div class="dropdown-trigger">
                  <button class="button is-rounded" aria-haspopup="true" aria-controls="dropdown-<?= md5($entry['_id']) ?>">
                    <span class="icon"><i class="fas fa-ellipsis-h" aria-hidden="true"></i></span>
                  </button>
                </div>
                <div class="dropdown-menu" id="dropdown-<?= md5($entry['_id']) ?>" role="menu">
                  <div class="dropdown-content">
                    <a class="dropdown-item" href="#" data-action="remove">Remove from Channel</a>
                    <a class="dropdown-item" href="#" data-action="debug">Debug</a>
                    <a class="dropdown-item disabled" href="#" data-action="">Mute this Person</a>
                    <a class="dropdown-item disabled" href="#" data-action="">Block this Person</a>
                    <a class="dropdown-item disabled" href="#" data-action="">Unfollow this Source</a>
                  </div>
                </div>
              </div>

              <?php if(isset($entry['url']) && !isset($entry['like-of']) && $responses_enabled): ?>
                <a href="#" class="button is-rounded" data-action="favorite"><span class="icon is-small"><i class="fas fa-star"></i></span></a>
                <a href="#" class="button is-rounded" data-action="repost"><span class="icon is-small"><i class="fas fa-retweet"></i></span></a>
                <a href="#" class="button is-rounded" data-action="reply"><span class="icon is-small"><i class="fas fa-reply"></i></span></a>
              <?php endif ?>
            </div>
            <div class="action-responses">
              <div class="new-reply hidden">
                <textarea class="textarea" rows="2"></textarea>
                <a style="font-size: 0.8em;" href="https://quill.p3k.io/new?reply=<?= urlencode($entry['url']) ?>" target="_blank">reply with quill</a>
                <div class="control" style="margin-top: 6px; float: right;">
                  <span class="counter"></span>
                  <button class="button is-primary is-small post-reply">Reply</button>
                </div>
                <div style="clear:both;"></div>
              </div>
            </div>
          </div>
