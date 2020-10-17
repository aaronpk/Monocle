<?php $this->layout('layout', ['title' => 'Monocle']) ?>

<link rel="stylesheet" href="/assets/timeline-styles.css">
<style>
#main-container {
  padding-top: 0;
  padding-bottom: 0;
  margin-left: auto;
}
.column {
  padding-top: 0;
}
.entry {
  margin-bottom: 8px;
}
.entry .context {
  font-size: 0.8em;
  padding: 4px;
}
.entry .author .author-name {
  font-size: 9pt;
}
.entry .author img {
  width: 30px;
  border-radius: 15px;
}
.entry .meta {
  font-size: 8pt;
}
</style>

<div id="window">

  <input type="checkbox" id="nav-trigger" class="nav-trigger" />

  <div id="main-container">

    <div class="column"><div class="inner">

      <? foreach($entries as $i=>$entry): ?>
        <? if(!isset($entry['like-of']) && !isset($entry['repost-of'])): ?>
          <div class="entry h-entry <?= isset($entry['like-of']) ? 'like-of' : '' ?> <?= isset($entry['_is_read']) && $entry['_is_read'] == 0 ? 'unread' : 'read' ?>" data-entry="<?= $i ?>" data-entry-id="<?= e($entry['_id']) ?>"
            data-is-read="<?= isset($entry['_is_read']) ? ($entry['_is_read'] ? 1 : 0) : 1 ?>">

            <?= $this->insert('timeline/reply-context', ['entry' => $entry]) ?>

            <?= $this->insert('timeline/author-card', ['entry' => $entry]) ?>

            <? /* ************************************************ */ ?>
            <? /* POST CONTENTS                                    */ ?>

            <?= $this->insert('timeline/checkin', ['entry' => $entry]) ?>

            <?= $this->insert('timeline/name-and-content', ['entry' => $entry]) ?>

            <?= $this->insert('timeline/audio', ['entry' => $entry]) ?>

            <?= $this->insert('timeline/photo-and-video', ['entry' => $entry]) ?>

            <?= $this->insert('timeline/quotation', ['entry' => $entry]) ?>

            <? /* ************************************************ */ ?>

            <?= $this->insert('timeline/meta', ['entry' => $entry]) ?>

          </div>
        <? endif ?>
      <? endforeach ?>

    </div></div>

  </div>
</div>

