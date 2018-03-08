                <<?= $tag . ' ' . (isset($href) ? ' href="'.$href.'"' : '') ?> class="dropdown-item" data-destination="<?= e($destination['uid']) ?>">
                  <span class="destination-card">
                    <? if(isset($destination['user'])): ?>
                      <? if(isset($destination['user']['photo'])): ?>
                        <img src="<?= e($destination['user']['photo']) ?>" width="40">
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
