<?php $this->layout('layout', ['title' => $title]) ?>

<link rel="stylesheet" href="/assets/timeline-styles.css">
<link rel="stylesheet" href="/assets/public-timeline.css">

<div id="window">

  <?= $this->insert('public_timeline_content', [
        'title' => $title,
        'channel' => [],
        'entries' => $entries,
        'paging' => $paging,
        'responses_enabled' => false
  ]); ?>

</div>
