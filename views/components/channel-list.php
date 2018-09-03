    <?php foreach($_SESSION['channels'] as $channel): ?>
      <li data-channel-uid="<?= e($channel['uid']) ?>">
        <a href="/channel/<?= e(urlencode($channel['uid'])) ?>">
          <?= e($channel['name']) ?>
          <?php if(isset($channel['unread'])): ?>
            <span class="tag is-info <?= is_bool($channel['unread']) ? 'is-dot' : 'is-rounded' ?> <?= isset($channel['unread']) && $channel['unread'] > 0 ? '' : 'is-hidden' ?>"><?= is_bool($channel['unread']) ? '' : $channel['unread'] ?></span>
          <?php endif ?>
        </a>
      </li>
    <?php endforeach; ?>
