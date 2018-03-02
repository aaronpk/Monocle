<?php $this->layout('layout', ['title' => $channel['name']]) ?>

<style>
html, body {
  width: 100%;
  height: 100vh;
  margin: 0;
  padding: 0;
  font-family: sans-serif;
}



.entry {
  margin-bottom: 12px;
  border: 1px #E6E6E6 solid;
  border-radius: 4px;
  background: #fff;
  line-height: 1.4;
  word-wrap: break-word;
}

.entry .author {
  padding: 6px 8px 0 8px;
}
.entry .author img {
  width: 40px;
  border-radius: 20px;
  border: 1px #e6e6e6 solid;
  vertical-align: middle;
}
.entry .author a {
  text-decoration: none;
}
.entry .author img {
  margin-left: 6px;
}
.entry .author .author-name {
  font-size: 10pt;
  line-height: 1.2;
  display: inline-block;
  vertical-align: middle;
}
.entry .author .author-name .name {
  display: block;
  font-weight: bold;
}
.entry .author .author-name .url {
  display: block;
}

.entry > .name {
  padding: 0 8px;
  margin: 0;
  margin-top: 12px;
  font-size: 16pt;
}

.entry .content {
  padding: 8px;
  margin-bottom: 0;
}
.entry .content.text {
  white-space: pre-wrap;
}
.entry p:first-child {
  margin-top: 0;
}
.entry p:last-child {
  margin-bottom: 0;
}
.entry .content img {
  max-width: 100%;
}
.entry .content blockquote {
  border-left: 4px #e6e6e6 solid;
  margin-left: 20px;
  padding-left: 8px;
}

.entry audio {
  width: 100%;
}

.entry .photo {
  width: 100%;
  margin-bottom: 6px;
}

.entry .meta {
  padding: 0 8px 8px 8px;
  color: #aaa;
  font-size: 9pt;
}
.entry .meta a {
  color: #aaa;
  text-decoration: none;
}

.entry .actions {
  padding: 8px;
  background: #f3f3f3;
}
.entry .actions .action-responses > div {
  margin-top: 12px;
}
.entry .action-buttons {
  text-align: right;
}

</style>

<div id="window">

  <input type="checkbox" id="nav-trigger" class="nav-trigger" />

  <div id="main-top">
    <label for="nav-trigger" id="nav-trigger-label"></label>
    <h2 class="title">
      <?= $channel['name'] ?>
      <a href="/" class="back"><span class="icon is-small"><i class="fas fa-home"></i></span></a>
    </h2>
  </div>

  <nav id="side-menu">
    <ul class="channels channel-list">
      <?= $this->insert('components/channel-list') ?>
    </ul>
  </nav>

  <div id="main-container">

    <div class="column"><div class="inner">

      <? foreach($entries as $i=>$entry): ?>
        <div class="entry" data-entry="<?= $i ?>" data-entry-id="<?= e($entry['_id']) ?>"
          data-is-read="<?= $entry['_is_read'] ? 1 : 0 ?>">

          <div class="author">
            <? if(!empty($entry['author']['name']) && !empty($entry['author']['photo']) && !empty($entry['author']['url'])): ?>
              <img src="<?= e($entry['author']['photo']) ?>">
              <div class="author-name">
                <a href="<?= e($entry['author']['url']) ?>" class="name"><?= e($entry['author']['name']) ?></a>
                <a href="<?= e($entry['author']['url']) ?>" class="url"><?= e(\p3k\url\display_url($entry['author']['url'])) ?></a>
              </div>
            <? elseif(!empty($entry['author']['url'])): ?>
              <div class="author-name">
                <a href="<?= e($entry['author']['url']) ?>" class="name"><?= e($entry['author']['name']) ?></a>
                <a href="<?= e($entry['author']['url']) ?>" class="url"><?= e(\p3k\url\display_url($entry['author']['url'])) ?></a>
              </div>
            <? else: ?>

            <? endif ?>
          </div>

          <? if(!empty($entry['name'])): ?>
            <h2 class="name"><?= e($entry['name']) ?></h2>
          <? endif ?>

          <? if(!empty($entry['content']['html'])): ?>
            <div class="content html"><?= $entry['content']['html'] ?></div>
          <? elseif(!empty($entry['content']['text'])): ?>
            <div class="content text"><?= e($entry['content']['text']) ?></div>
          <? endif ?>

          <? if(!empty($entry['audio'])): ?>
            <? foreach($entry['audio'] as $audio): ?>
              <audio src="<?= e($audio) ?>" controls></audio>
            <? endforeach ?>
          <? endif ?>

          <? if(isset($entry['photo'])): ?>
            <div class="photos">
              <? foreach($entry['photo'] as $photo): ?>
                <img src="<?= $photo ?>" class="photo">
              <? endforeach ?>
              <div class="photoclear"></div>
            </div>
          <? endif ?>

          <div class="meta">
            <? if(!empty($entry['published'])): ?>
              <a href="<?= e($entry['url']) ?>">
                <?= display_date('F j, Y g:ia P', $entry['published']) ?>
              </a>
            <? else: ?>

            <? endif ?>
          </div>

          <div class="actions" data-url="<?= e($entry['url']) ?>">
            <div class="action-buttons">
              <a href="#" class="button is-rounded" data-action="favorite"><span class="icon is-small"><i class="fas fa-thumbs-up"></i></span></a>
              <a href="#" class="button is-rounded" data-action="repost"><span class="icon is-small"><i class="fas fa-retweet"></i></span></a>
              <a href="#" class="button is-rounded" data-action="reply"><span class="icon is-small"><i class="fas fa-reply"></i></span></a>
            </div>
            <div class="action-responses">
              <div class="new-reply hidden">
                <textarea class="textarea" rows="2"></textarea>
                <a style="font-size: 0.8em;" href="https://quill.p3k.io/new?reply=<?= urlencode($entry['url']) ?>" target="_blank">reply with quill</a>
                <div class="control" style="margin-top: 6px; float: right;">
                  <button class="button is-primary is-small post-reply">Reply</button>
                </div>
                <div style="clear:both;"></div>
              </div>
            </div>
          </div>
        </div>
      <? endforeach ?>

    </div></div>

    <!-- TODO: fixed bottom bar showing current account context -->
    <div id="main-bottom">
      Input area
    </div>
  </div>
</div>

