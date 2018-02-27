<?php $this->layout('layout', ['title' => 'Monocle']) ?>

<style>
.channels {
  line-height: 1.6;
}
.channels a {
  text-decoration: none;
}
</style>

<ul class="channels">
<?php foreach($_SESSION['channels'] as $channel): ?>
  <li>
    <a href="/channel/<?= e($channel['uid']) ?>"><?= e($channel['name']) ?></a>
    <?= isset($channel['unread']) ? '('.$channel['unread'].' unread)' : '' ?>
  </li>
<?php endforeach; ?>
</ul>

<br><br>

<a href="/logout">Log Out</a>

