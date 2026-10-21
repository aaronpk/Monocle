          <?php if(!empty($entry['checkin'])): ?>
            <div class="content checkin p-checkin h-card">
              <div class="name">
                <?= fa('map-marker-alt') ?>
                <span class="p-name"><?= e($entry['checkin']['name'] ?? '(unknown)') ?></span>
              </div>
              <?php if(!empty($entry['checkin']['latitude'])): ?>
                <div class="map">
                  <img src="https://atlas.p3k.io/map/img?marker[]=lat:<?= (float)$entry['checkin']['latitude'] ?>;lng:<?= (float)($entry['checkin']['longitude'] ?? 0) ?>;icon:small-blue-cutout&basemap=gray&width=558&height=220&zoom=16">
                </div>
                <data class="p-latitude" value="<?= (float)$entry['checkin']['latitude'] ?>"/>
                <data class="p-longitude" value="<?= (float)($entry['checkin']['longitude'] ?? 0) ?>"/>
              <?php endif ?>
            </div>
          <?php endif ?>
