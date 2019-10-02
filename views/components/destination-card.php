                <<?= $tag . ' ' . (isset($href) ? ' href="'.$href.'"' : '') ?> class="dropdown-item <?= $selected ? 'selected' : '' ?>" data-destination="<?= e($destination['uid']) ?>" data-destination-ch="<?= strtolower(substr($destination['user']['name'] ?? $destination['name'], 0, 1)) ?>">
                  <span class="destination-card">
                    <? if(isset($destination['user'])): ?>
                      <? if(isset($destination['user']['photo'])): ?>
                        <img src="<?= e(image_proxy($destination['user']['photo'], '80x80')) ?>" width="40">
                      <? else: ?>
                        <img src="/assets/no-profile-photo.png" width="40">
                      <? endif ?>
                      <span class="name"><?= e($destination['user']['name']) ?></span>
                    <? else: ?>
                      <img src="/assets/no-profile-photo.png" width="40">
                      <span class="name"><?= e($destination['name']) ?></span>
                    <? endif ?>
                  </span>
                </<?= $tag ?>>
