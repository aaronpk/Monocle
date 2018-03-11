<?php $this->layout('layout', ['title' => $title]) ?>

<link rel="stylesheet" href="/assets/timeline-styles.css">
<link rel="stylesheet" href="/assets/public-timeline.css">

<div id="window">

  <div id="public-container">

    <div class="column"><div class="inner">

      <? foreach($entries as $i=>$entry): ?>
        <div class="entry h-entry <?= isset($entry['like-of']) ? 'like-of' : '' ?> <?= isset($entry['_is_read']) && $entry['_is_read'] == 0 ? 'unread' : 'read' ?>" data-entry="<?= $i ?>" data-entry-id="<?= e($entry['_id']) ?>"
          data-is-read="<?= isset($entry['_is_read']) ? ($entry['_is_read'] ? 1 : 0) : 1 ?>">

          <? if(isset($entry['like-of'])): ?>

            <? /* TODO: if expanded content of the like-of is available, render that post instead */ ?>
            <?= $this->insert('timeline/compact-like', ['entry' => $entry]) ?>
            <?= $this->insert('timeline/meta', ['entry' => $entry]) ?>

          <? else: ?>
            <?= $this->insert('timeline/reply-context', ['entry' => $entry]) ?>

            <?= $this->insert('timeline/author-card', ['entry' => $entry]) ?>

            <? /* ************************************************ */ ?>
            <? /* POST CONTENTS                                    */ ?>

            <?= $this->insert('timeline/checkin', ['entry' => $entry]) ?>

            <?= $this->insert('timeline/name-and-content', ['entry' => $entry]) ?>

            <?= $this->insert('timeline/audio', ['entry' => $entry]) ?>

            <?= $this->insert('timeline/photo-and-video', ['entry' => $entry]) ?>

            <? /* ************************************************ */ ?>

            <?= $this->insert('timeline/meta', ['entry' => $entry]) ?>
          <? endif ?>

          <pre style="display: none;" class="source"><?= j($entry) ?></pre>
        </div>
      <? endforeach ?>

      <? if(isset($paging['after'])): ?>
      <nav class="pagination" role="navigation" aria-label="pagination">
        <a class="pagination-next" href="?after=<?= e($paging['after']) ?>">More</a>
      </nav>
      <? endif ?>

    </div></div>

  </div>

</div>
