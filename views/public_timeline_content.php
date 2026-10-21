  <div id="public-container">

    <div class="column"><div class="inner">

      <?php foreach($entries as $i=>$entry): ?>
        <div class="entry h-entry <?= isset($entry['like-of']) ? 'like-of' : '' ?>" data-entry="<?= $i ?>" data-entry-id="<?= e($entry['_id'] ?? '') ?>">

          <?php if(isset($entry['like-of'])): ?>

            <?php /* TODO: if expanded content of the like-of is available, render that post instead */ ?>
            <?= $this->insert('timeline/compact-like', ['entry' => $entry]) ?>
            <?= $this->insert('timeline/meta', ['entry' => $entry]) ?>

          <?php else: ?>

            <?php if(isset($entry['repost-of'])): ?>

              <div class="repost context">
                <a href="<?= e($entry['repost-of'][0]) ?>"><?= fa('retweet') ?></a>
                <?php if(!empty($entry['author']['url'])): ?>
                  <a href="<?= e($entry['author']['url']) ?>" class="u-url p-name">
                    <?= e($entry['author']['name'] ?? \p3k\url\display_url($entry['author']['url'])) ?>
                  </a>
                <?php elseif(!empty($entry['author']['name'])): ?>
                  <?= e($entry['author']['name']) ?>
                <?php else: ?>
                  someone
                <?php endif ?>
                reposted
              </div>

              <?php
                /* overwrite $entry so that the reposted post is displayed instead */
                if(isset($entry['refs'][$entry['repost-of'][0]])) {
                  $_id = $entry['_id'];
                  $entry = $entry['refs'][$entry['repost-of'][0]];
                  $entry['_id'] = $_id;
                }
              ?>

            <?php endif ?>

            <?= $this->insert('timeline/reply-context', ['entry' => $entry]) ?>

            <?= $this->insert('timeline/author-card', ['entry' => $entry]) ?>

            <?php /* ************************************************ */ ?>
            <?php /* POST CONTENTS                                    */ ?>

            <?= $this->insert('timeline/checkin', ['entry' => $entry]) ?>

            <?php if(isset($mode) && $mode == 'preview'): ?>
              <?= $this->insert('timeline/name-and-content', ['entry' => $entry]) ?>
            <?php else: ?>
              <?= $this->insert('timeline/name-and-summary', ['entry' => $entry]) ?>
            <?php endif ?>

            <?= $this->insert('timeline/audio', ['entry' => $entry]) ?>

            <?= $this->insert('timeline/photo-and-video', ['entry' => $entry]) ?>

            <?= $this->insert('timeline/quotation', ['entry' => $entry]) ?>

            <?php /* ************************************************ */ ?>

            <?= $this->insert('timeline/meta', ['entry' => $entry]) ?>
          <?php endif ?>

          <pre style="display: none;" class="source"><?= j($entry) ?></pre>
        </div>
      <?php endforeach ?>

      <?php if(isset($paging['after'])): ?>
      <nav class="pagination" role="navigation" aria-label="pagination">
        <a class="pagination-next" href="?after=<?= e($paging['after']) ?>">More</a>
      </nav>
      <?php endif ?>

    </div></div>

  </div>
