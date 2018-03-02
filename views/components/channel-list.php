    <?php foreach($_SESSION['channels'] as $channel): ?>
      <li data-channel-uid="<?= e($channel['uid']) ?>">
        <a href="/channel/<?= e($channel['uid']) ?>"><?= e($channel['name']) ?></a>
        <span class="tag is-rounded is-info <?= isset($channel['unread']) && $channel['unread'] > 0 ? '' : 'is-hidden' ?>"><?= $channel['unread']?></span>
      </li>
    <?php endforeach; ?>
