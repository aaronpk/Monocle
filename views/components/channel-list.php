    <?php foreach($_SESSION['channels'] as $channel): ?>
      <li>
        <a href="/channel/<?= e($channel['uid']) ?>"><?= e($channel['name']) ?></a>
        <? if(isset($channel['unread']) && $channel['unread'] > 0): ?>
          <span class="tag is-rounded is-info"><?= $channel['unread']?></span>
        <? endif ?>
      </li>
    <?php endforeach; ?>
