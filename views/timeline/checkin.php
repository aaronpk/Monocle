          <? if(!empty($entry['checkin'])): ?>
            <div class="content checkin">
              <div class="name">
                <i class="fas fa-map-marker-alt"></i>
                <?= e($entry['checkin']['name'] ?? '(unknown)') ?>
              </div>
              <? if(!empty($entry['checkin']['latitude'])): ?>
                <div class="map">
                  <img src="https://atlas.p3k.io/map/img?marker[]=lat:<?= (float)$entry['checkin']['latitude'] ?>;lng:<?= (float)$entry['checkin']['longitude'] ?>;icon:small-blue-cutout&basemap=gray&width=558&height=220&zoom=16">
                </div>
              <? endif ?>
            </div>
          <? endif ?>
