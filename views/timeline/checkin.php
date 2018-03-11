          <? if(!empty($entry['checkin'])): ?>
            <div class="content checkin p-checkin h-card">
              <div class="name">
                <i class="fas fa-map-marker-alt"></i>
                <span class="p-name"><?= e($entry['checkin']['name'] ?? '(unknown)') ?></span>
              </div>
              <? if(!empty($entry['checkin']['latitude'])): ?>
                <div class="map">
                  <img src="https://atlas.p3k.io/map/img?marker[]=lat:<?= (float)$entry['checkin']['latitude'] ?>;lng:<?= (float)$entry['checkin']['longitude'] ?>;icon:small-blue-cutout&basemap=gray&width=558&height=220&zoom=16">
                </div>
                <data class="p-latitude" value="<?= (float)$entry['checkin']['latitude'] ?>"/>
                <data class="p-longitude" value="<?= (float)$entry['checkin']['longitude'] ?>"/>
              <? endif ?>
            </div>
          <? endif ?>
