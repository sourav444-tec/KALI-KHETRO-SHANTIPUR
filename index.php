<?php
require __DIR__ . '/config/database.php';
$pujas = $pdo->query("SELECT * FROM pujas WHERE published=1 ORDER BY featured DESC, name_en ASC")->fetchAll();
$services = $pdo->query("SELECT * FROM services WHERE published=1 ORDER BY FIELD(service_type,'Police','Hospital','Fire Brigade','Railway Station','Ferry Ghat','Bus Stop','Road Access'), name_en")->fetchAll();
$partners = $pdo->query("SELECT * FROM partners WHERE published=1 ORDER BY sort_order, name")->fetchAll();
$team = $pdo->query("SELECT * FROM team_members WHERE published=1 ORDER BY sort_order, name")->fetchAll();
$contacts = $pdo->query("SELECT * FROM emergency_contacts WHERE published=1 ORDER BY sort_order")->fetchAll();
$ratingRows = $pdo->query('SELECT puja_id, COUNT(*) AS rating_count, ROUND(AVG(rating),1) AS avg_rating FROM puja_ratings GROUP BY puja_id')->fetchAll();
$pujaRatings = []; foreach ($ratingRows as $rr) { $pujaRatings[(int)$rr['puja_id']] = $rr; }
?>
<!doctype html>
<html lang="bn">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Kali Khetro Santipur | কালী ক্ষেত্র শান্তিপুর</title>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""><link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="topbar">
  <a class="brand" href="index.php"><span class="trident">♆</span><span data-en="KALI KHETRO SANTIPUR" data-bn="কালী ক্ষেত্র শান্তিপুর">কালী ক্ষেত্র শান্তিপুর</span></a>
  <nav class="navlinks">
    <a href="#home" data-en="Home" data-bn="হোম">হোম</a><a href="#pujas" data-en="Puja Directory" data-bn="পুজোর তালিকা">পুজোর তালিকা</a>
    <a href="#services" data-en="Local Services" data-bn="স্থানীয় পরিষেবা">স্থানীয় পরিষেবা</a><a href="#partners" data-en="Partners" data-bn="সহযোগী">সহযোগী</a><a href="#team" data-en="Our Team" data-bn="আমাদের দল">আমাদের দল</a>
  </nav>
  <div class="header-actions"><button id="langToggle" class="lang-btn" type="button">EN</button><a class="admin-link" href="admin/login.php" aria-label="Admin login" title="Admin login">♙ <span data-en="Admin" data-bn="অ্যাডমিন">অ্যাডমিন</span></a><button id="menuToggle" class="menu-toggle" aria-label="Toggle menu">☰</button></div>
</header>

<main>
<section class="hero" id="home">
  <div class="hero-slides" aria-hidden="true">
    <div class="hero-slide hero-main active"></div>
  </div>
  <div class="hero-content">
    <p class="eyebrow" data-en="THE SPIRIT OF SHANTIPUR" data-bn="শান্তিপুরের ঐতিহ্য ও ভক্তি">শান্তিপুরের ঐতিহ্য ও ভক্তি</p>
    <h1 data-en="Discover the Kali Puja heritage of Santipur" data-bn="শান্তিপুরের কালীপুজোর ঐতিহ্য আবিষ্কার করুন">শান্তিপুরের কালীপুজোর ঐতিহ্য আবিষ্কার করুন</h1>
    <p class="hero-copy" data-en="Explore historic pujas, find your route, and plan a memorable night of devotion." data-bn="ঐতিহ্যবাহী কালীপুজো খুঁজুন, পথ চিনে নিন এবং সাজিয়ে তুলুন আপনার পুজো পরিক্রমা।">ঐতিহ্যবাহী কালীপুজো খুঁজুন, পথ চিনে নিন এবং সাজিয়ে তুলুন আপনার পুজো পরিক্রমা।</p>
    <div class="hero-actions"><a class="btn btn-gold" href="#pujas" data-en="Explore Pujas →" data-bn="পুজো খুঁজুন →">পুজো খুঁজুন →</a><a class="btn btn-glass" href="#services" data-en="Plan your route" data-bn="পথ পরিকল্পনা করুন">পথ পরিকল্পনা করুন</a></div>
  </div>
  <div class="hero-stamp">নদিয়া · পশ্চিমবঙ্গ<br><small>ঐতিহ্যের আলোয় শান্তিপুর</small></div>
</section>

<section class="section intro-row">
  <div><p class="eyebrow" data-en="A LOCAL GUIDE, MADE WITH CARE" data-bn="আপনার এলাকার নিজস্ব গাইড">আপনার এলাকার নিজস্ব গাইড</p><h2 data-en="One city. Countless Kali Puja stories." data-bn="এক শহর, কালীপুজোর অগণিত গল্প।">এক শহর, কালীপুজোর অগণিত গল্প।</h2></div>
  <p data-en="Find puja locations, learn their history, and check essential services before you head out. Information is curated and updated by our admin team." data-bn="পুজোর অবস্থান জানুন, ইতিহাস পড়ুন এবং বেরোনোর আগে জরুরি পরিষেবার তথ্য দেখে নিন। আমাদের অ্যাডমিন দল তথ্য আপডেট করে।">পুজোর অবস্থান জানুন, ইতিহাস পড়ুন এবং বেরোনোর আগে জরুরি পরিষেবার তথ্য দেখে নিন। আমাদের অ্যাডমিন দল তথ্য আপডেট করে।</p>
</section>

<section class="section map-section" id="map-section">
  <div class="section-heading"><div><p class="eyebrow" data-en="EXPLORE SANTIPUR" data-bn="শান্তিপুর ঘুরে দেখুন">শান্তিপুর ঘুরে দেখুন</p><h2 data-en="Kali Puja map & nearby services" data-bn="কালীপুজোর মানচিত্র ও কাছাকাছি পরিষেবা">কালীপুজোর মানচিত্র ও কাছাকাছি পরিষেবা</h2></div><a class="btn btn-glass" href="https://www.openstreetmap.org/#map=14/23.2472/88.4330" target="_blank" rel="noopener" data-en="Open full map ↗" data-bn="সম্পূর্ণ মানচিত্র খুলুন ↗">সম্পূর্ণ মানচিত্র খুলুন ↗</a></div>
  <p class="map-intro" data-en="Puja and service locations appear here when the admin has entered coordinates. Verify details before travelling." data-bn="অ্যাডমিন অবস্থানের স্থানাঙ্ক যোগ করলে পুজো ও পরিষেবার অবস্থান এখানে দেখা যাবে। যাত্রার আগে তথ্য যাচাই করুন।">অ্যাডমিন অবস্থানের স্থানাঙ্ক যোগ করলে পুজো ও পরিষেবার অবস্থান এখানে দেখা যাবে। যাত্রার আগে তথ্য যাচাই করুন।</p>
  <div class="map-layout"><div class="map-frame"><div id="pujaMap" role="application" aria-label="Santipur Kali Puja and local services map"></div><div class="map-legend"><span><i class="legend-dot puja-dot"></i><span data-en="Kali Puja" data-bn="কালীপুজো">কালীপুজো</span></span><span><i class="legend-dot service-dot"></i><span data-en="Local services" data-bn="স্থানীয় পরিষেবা">স্থানীয় পরিষেবা</span></span></div></div>
  <aside class="map-side"><h3 data-en="Explore the map" data-bn="মানচিত্রে খুঁজুন">মানচিত্রে খুঁজুন</h3><p data-en="Filter locations and open directions to a selected marker." data-bn="অবস্থান বেছে নিন এবং নির্বাচিত চিহ্নের পথ খুলুন।">অবস্থান বেছে নিন এবং নির্বাচিত চিহ্নের পথ খুলুন।</p><div class="map-actions"><button class="btn btn-gold" type="button" id="showAllMap" data-en="Show all locations" data-bn="সব অবস্থান দেখুন">সব অবস্থান দেখুন</button><button class="btn btn-glass" type="button" id="showPujasMap" data-en="Show Pujas only" data-bn="শুধু পুজো দেখুন">শুধু পুজো দেখুন</button><button class="btn btn-glass" type="button" id="showServicesMap" data-en="Show services only" data-bn="শুধু পরিষেবা দেখুন">শুধু পরিষেবা দেখুন</button></div><small data-en="Map data is not a substitute for official emergency guidance." data-bn="মানচিত্রের তথ্য সরকারি জরুরি নির্দেশনার বিকল্প নয়।">মানচিত্রের তথ্য সরকারি জরুরি নির্দেশনার বিকল্প নয়।</small></aside></div>
</section>

<section class="section" id="pujas">
  <div class="section-heading"><div><p class="eyebrow" data-en="FIND YOUR NEXT STOP" data-bn="আপনার পরের গন্তব্য খুঁজুন">আপনার পরের গন্তব্য খুঁজুন</p><h2 data-en="Kali Puja Directory" data-bn="কালীপুজোর তালিকা">কালীপুজোর তালিকা</h2></div><span class="count-pill"><?= count($pujas) ?> <span data-en="listed" data-bn="টি পুজো">টি পুজো</span></span></div>
  <div class="filterbar"><label class="searchbox">⌕ <input id="pujaSearch" type="search" placeholder="পুজোর নাম বা এলাকা খুঁজুন..." data-placeholder-en="Search puja or area..." data-placeholder-bn="পুজোর নাম বা এলাকা খুঁজুন..."></label>
  <select id="categoryFilter" aria-label="Filter category"><option value="" data-en="All categories" data-bn="সব বিভাগ">সব বিভাগ</option><option>Heritage</option><option>Traditional</option><option>Popular</option><option>Theme</option></select></div>
  <div class="puja-grid" id="pujaGrid">
  <?php foreach ($pujas as $p): ?>
    <article class="puja-card" data-name="<?= e(mb_strtolower($p['name_en'].' '.$p['name_bn'].' '.$p['address'])) ?>" data-category="<?= e($p['category']) ?>">
      <div class="puja-image" <?php if ($p['photo']): ?>style="background-image:url('<?= e($p['photo']) ?>')"<?php endif; ?>>
        <?php if (!$p['photo']): ?><span class="image-symbol">ॐ</span><?php endif; ?><span class="category-tag"><?= e($p['category']) ?></span>
      </div>
      <div class="puja-card-body"><h3 data-en="<?= e($p['name_en']) ?>" data-bn="<?= e($p['name_bn'] ?: $p['name_en']) ?>"><?= e($p['name_bn'] ?: $p['name_en']) ?></h3>
      <p><?= e($p['address']) ?></p><p class="muted" data-en="<?= e($p['years_continued'] ? 'Tradition: '.$p['years_continued'] : 'Details maintained by our team') ?>" data-bn="<?= e($p['years_continued'] ? 'ঐতিহ্য: '.$p['years_continued'] : 'তথ্য আমাদের দল দ্বারা সংরক্ষিত') ?>"><?= e($p['years_continued'] ? 'ঐতিহ্য: '.$p['years_continued'] : 'তথ্য আমাদের দল দ্বারা সংরক্ষিত') ?></p>
      <?php $pr = $pujaRatings[(int)$p['id']] ?? ['rating_count'=>0,'avg_rating'=>0]; ?>
      <div class="rating-summary"><span aria-label="Average rating">★ <?= $pr['rating_count'] ? e((string)$pr['avg_rating']) : '—' ?>/5</span><small><?= (int)$pr['rating_count'] ?> ratings</small></div>
      <form class="puja-rating-form" action="submit-rating.php" method="post"><input type="hidden" name="puja_id" value="<?= (int)$p['id'] ?>"><label for="rating-<?= (int)$p['id'] ?>" data-en="Rate this Puja" data-bn="এই পুজোকে রেটিং দিন">এই পুজোকে রেটিং দিন</label><select id="rating-<?= (int)$p['id'] ?>" name="rating" required aria-label="Puja rating"><option value="">Select / বাছুন</option><?php for($star=5;$star>=1;$star--): ?><option value="<?= $star ?>"><?= $star ?> ★</option><?php endfor; ?></select><button class="text-button" type="submit" data-en="Submit rating →" data-bn="রেটিং জমা দিন →">রেটিং জমা দিন →</button></form>
      <button class="text-button details-toggle" type="button" data-target="details-<?= (int)$p['id'] ?>" data-en="View details + " data-bn="বিস্তারিত দেখুন + ">বিস্তারিত দেখুন +</button>
      <div class="puja-details" id="details-<?= (int)$p['id'] ?>"><p data-en="<?= e($p['description_en'] ?: 'More information will be added by the admin.') ?>" data-bn="<?= e($p['description_bn'] ?: 'অ্যাডমিন আরও তথ্য যোগ করবেন।') ?>"><?= e($p['description_bn'] ?: 'অ্যাডমিন আরও তথ্য যোগ করবেন।') ?></p><?php if ($p['theme']): ?><p><strong data-en="Theme:" data-bn="ভাবনা:">ভাবনা:</strong> <?= e($p['theme']) ?></p><?php endif; ?><?php if ($p['contact']): ?><p><strong data-en="Contact:" data-bn="যোগাযোগ:">যোগাযোগ:</strong> <?= e($p['contact']) ?></p><?php endif; ?><?php if (!empty($p['map_url'])): ?><a class="text-button" target="_blank" rel="noopener noreferrer" href="<?= e($p['map_url']) ?>" data-en="View on Google Maps ↗" data-bn="Google Maps-এ দেখুন ↗">Google Maps-এ দেখুন ↗</a><?php elseif ($p['lat'] && $p['lng']): ?><a class="text-button" target="_blank" rel="noopener noreferrer" href="https://www.openstreetmap.org/?mlat=<?= e((string)$p['lat']) ?>&mlon=<?= e((string)$p['lng']) ?>#map=16/<?= e((string)$p['lat']) ?>/<?= e((string)$p['lng']) ?>" data-en="Open map ↗" data-bn="ম্যাপে দেখুন ↗">ম্যাপে দেখুন ↗</a><?php endif; ?></div></div>
    </article>
  <?php endforeach; ?>
  </div>
  <?php if (!$pujas): ?><div class="empty-state"><span>✦</span><h3 data-en="Puja listings are coming soon" data-bn="পুজোর তালিকা শীঘ্রই আসছে">পুজোর তালিকা শীঘ্রই আসছে</h3><p data-en="The admin can add the first Puja from the dashboard." data-bn="অ্যাডমিন ড্যাশবোর্ড থেকে প্রথম পুজোর তথ্য যোগ করতে পারবেন।">অ্যাডমিন ড্যাশবোর্ড থেকে প্রথম পুজোর তথ্য যোগ করতে পারবেন।</p></div><?php endif; ?>
</section>

<section class="section services-section" id="services">
  <div class="section-heading"><div><p class="eyebrow" data-en="PLAN WITH CONFIDENCE" data-bn="নিরাপদে পরিকল্পনা করুন">নিরাপদে পরিকল্পনা করুন</p><h2 data-en="Local services & transport" data-bn="স্থানীয় পরিষেবা ও যাতায়াত">স্থানীয় পরিষেবা ও যাতায়াত</h2></div><span class="count-pill" data-en="Admin-updated" data-bn="অ্যাডমিন আপডেট">অ্যাডমিন আপডেট</span></div>
  <div class="service-grid">
  <?php
  $serviceIcons=['Police'=>'✥','Hospital'=>'✚','Fire Brigade'=>'♨','Railway Station'=>'▣','Ferry Ghat'=>'≋','Bus Stop'=>'▤','Road Access'=>'↗','Other'=>'✦'];
  foreach ($services as $s): ?>
    <article class="service-card"><span class="service-icon"><?= $serviceIcons[$s['service_type']] ?? '✦' ?></span><div><span class="service-type"><?= e($s['service_type']) ?></span><h3 data-en="<?= e($s['name_en']) ?>" data-bn="<?= e($s['name_bn'] ?: $s['name_en']) ?>"><?= e($s['name_bn'] ?: $s['name_en']) ?></h3><p><?= e($s['address']) ?></p><?php if ($s['distance']): ?><p class="muted"><?= e($s['distance']) ?></p><?php endif; ?><?php if ($s['phone']): ?><a href="tel:<?= e(preg_replace('/[^0-9+]/','',$s['phone'])) ?>" class="text-button"><?= e($s['phone']) ?> · <span data-en="Call" data-bn="ফোন">ফোন</span></a><?php endif; ?><?php if ($s['lat'] && $s['lng']): ?><a target="_blank" rel="noopener" class="text-button" href="https://www.openstreetmap.org/?mlat=<?= e((string)$s['lat']) ?>&mlon=<?= e((string)$s['lng']) ?>#map=16/<?= e((string)$s['lat']) ?>/<?= e((string)$s['lng']) ?>" data-en="View map ↗" data-bn="ম্যাপে দেখুন ↗">ম্যাপে দেখুন ↗</a><?php endif; ?></div></article>
  <?php endforeach; ?>
  </div>
  <?php if (!$services): ?><p class="note" data-en="Service details will appear here after the admin adds verified local information." data-bn="অ্যাডমিন যাচাই করা স্থানীয় তথ্য যোগ করলে পরিষেবার বিবরণ এখানে দেখা যাবে।">অ্যাডমিন যাচাই করা স্থানীয় তথ্য যোগ করলে পরিষেবার বিবরণ এখানে দেখা যাবে।</p><?php endif; ?>
</section>

<section class="sos-band"><div><p class="eyebrow" data-en="HELP WHEN IT MATTERS" data-bn="জরুরি মুহূর্তে সহায়তা">জরুরি মুহূর্তে সহায়তা</p><h2 data-en="Keep emergency contacts close." data-bn="জরুরি যোগাযোগ হাতের কাছে রাখুন।">জরুরি যোগাযোগ হাতের কাছে রাখুন।</h2><p data-en="For a life-threatening emergency in India, call 112. Check local details and follow official instructions." data-bn="ভারতে জরুরি সহায়তার জন্য ১১২ নম্বরে ফোন করুন। স্থানীয় তথ্য যাচাই করুন এবং সরকারি নির্দেশ মেনে চলুন।">ভারতে জরুরি সহায়তার জন্য ১১২ নম্বরে ফোন করুন। স্থানীয় তথ্য যাচাই করুন এবং সরকারি নির্দেশ মেনে চলুন।</p></div><button class="btn btn-danger" id="openSOS" type="button">SOS · <span data-en="Emergency help" data-bn="জরুরি সহায়তা">জরুরি সহায়তা</span></button></section>

<section class="section" id="partners"><div class="section-heading"><div><p class="eyebrow" data-en="WITH OUR GRATITUDE" data-bn="কৃতজ্ঞতার সঙ্গে">কৃতজ্ঞতার সঙ্গে</p><h2 data-en="Media partners, collaborators & sponsors" data-bn="মিডিয়া পার্টনার, সহযোগী ও স্পনসর">মিডিয়া পার্টনার, সহযোগী ও স্পনসর</h2></div></div>
<div class="partner-grid"><?php foreach($partners as $partner): ?><a class="partner-card" href="<?= e($partner['website'] ?: '#partners') ?>" <?= $partner['website'] ? 'target="_blank" rel="noopener"' : '' ?>><?php if($partner['logo']): ?><img src="<?= e($partner['logo']) ?>" alt="<?= e($partner['name']) ?>"><?php else: ?><span class="partner-monogram"><?= e(mb_substr($partner['name'],0,1)) ?></span><?php endif; ?><span class="partner-type"><?= e($partner['partner_type']) ?></span><strong><?= e($partner['name']) ?></strong><?php if($partner['description']): ?><small><?= e($partner['description']) ?></small><?php endif; ?></a><?php endforeach; ?></div>
<?php if(!$partners): ?><p class="note" data-en="Partner logos and names will be published here by the admin." data-bn="অ্যাডমিন এখানে সহযোগীদের নাম ও লোগো প্রকাশ করবেন।">অ্যাডমিন এখানে সহযোগীদের নাম ও লোগো প্রকাশ করবেন।</p><?php endif; ?></section>

<section class="section team-section" id="team"><div class="section-heading"><div><p class="eyebrow" data-en="THE PEOPLE BEHIND THE PROJECT" data-bn="এই উদ্যোগের নেপথ্যের মানুষ">এই উদ্যোগের নেপথ্যের মানুষ</p><h2 data-en="Our team" data-bn="আমাদের দল">আমাদের দল</h2></div></div><div class="team-grid"><?php foreach($team as $member): ?><article class="team-card"><?php if($member['photo']): ?><img src="<?= e($member['photo']) ?>" alt="<?= e($member['name']) ?>"><?php else: ?><div class="team-avatar"><?= e(mb_substr($member['name'],0,1)) ?></div><?php endif; ?><h3><?= e($member['name']) ?></h3><p class="team-role"><?= e($member['role']) ?></p><p><?= e($member['bio']) ?></p></article><?php endforeach; ?></div><?php if(!$team): ?><p class="note" data-en="Our team profiles will be added soon." data-bn="আমাদের দলের পরিচিতি শীঘ্রই যোগ করা হবে।">আমাদের দলের পরিচিতি শীঘ্রই যোগ করা হবে।</p><?php endif; ?></section>

<section class="section feedback-section"><div><p class="eyebrow" data-en="HELP US IMPROVE" data-bn="আরও ভালো করতে সাহায্য করুন">আরও ভালো করতে সাহায্য করুন</p><h2 data-en="Share feedback" data-bn="আপনার মতামত জানান">আপনার মতামত জানান</h2></div><form class="feedback-form" id="feedback" action="submit-feedback.php" method="post"><input name="name" placeholder="নাম / Name (optional)" maxlength="120"><input name="email" type="email" placeholder="ইমেল / Email (optional)" maxlength="180"><label for="feedbackType" data-en="Feedback type" data-bn="মতামতের ধরন">মতামতের ধরন</label><select id="feedbackType" name="feedback_type" required><option value="Website">Website / ওয়েবসাইট</option><option value="Map">Map / মানচিত্র</option><option value="Puja">Puja information / পুজোর তথ্য</option><option value="Transport">Transport / যাতায়াত</option><option value="Helpline">Helpline / হেল্পলাইন</option><option value="Suggestion">Suggestion / পরামর্শ</option><option value="Problem">Report a problem / সমস্যা জানান</option></select><label for="feedbackRating" data-en="Rate your experience (optional)" data-bn="আপনার অভিজ্ঞতার রেটিং (ঐচ্ছিক)">আপনার অভিজ্ঞতার রেটিং (ঐচ্ছিক)</label><select id="feedbackRating" name="rating"><option value="">No rating / রেটিং নয়</option><option value="5">★★★★★ 5</option><option value="4">★★★★ 4</option><option value="3">★★★ 3</option><option value="2">★★ 2</option><option value="1">★ 1</option></select><textarea name="message" required placeholder="আপনার মতামত লিখুন / Your feedback" maxlength="3000"></textarea><button class="btn btn-gold" type="submit" data-en="Send feedback →" data-bn="মতামত পাঠান →">মতামত পাঠান →</button></form></section>
</main>

<footer class="footer"><a class="brand" href="#home"><span class="trident">♆</span><span data-en="KALI KHETRO SANTIPUR" data-bn="কালী ক্ষেত্র শান্তিপুর">কালী ক্ষেত্র শান্তিপুর</span></a><p data-en="A community guide to the Kali Puja heritage of Santipur, Nadia." data-bn="নদিয়ার শান্তিপুরের কালীপুজোর ঐতিহ্য নিয়ে একটি স্থানীয় গাইড।">নদিয়ার শান্তিপুরের কালীপুজোর ঐতিহ্য নিয়ে একটি স্থানীয় গাইড।</p><div class="footer-links"><a href="#pujas" data-en="Pujas" data-bn="পুজো">পুজো</a><a href="#services" data-en="Services" data-bn="পরিষেবা">পরিষেবা</a><a href="#partners" data-en="Partners" data-bn="সহযোগী">সহযোগী</a><a href="admin/login.php" data-en="Admin" data-bn="অ্যাডমিন">অ্যাডমিন</a></div><small>© <?= date('Y') ?> Kali Khetro Santipur · <span data-en="Built for the community" data-bn="সবার জন্য তৈরি">সবার জন্য তৈরি</span></small></footer>

<div class="sos-modal" id="sosModal" aria-hidden="true"><div class="sos-dialog" role="dialog" aria-modal="true" aria-labelledby="sosTitle"><button class="modal-close" id="closeSOS" aria-label="Close">×</button><p class="eyebrow">EMERGENCY · জরুরি</p><h2 id="sosTitle" data-en="Emergency contacts" data-bn="জরুরি যোগাযোগ">জরুরি যোগাযোগ</h2><p data-en="If someone is in immediate danger, call the official emergency number." data-bn="কেউ তাৎক্ষণিক বিপদে থাকলে সরকারি জরুরি নম্বরে ফোন করুন।">কেউ তাৎক্ষণিক বিপদে থাকলে সরকারি জরুরি নম্বরে ফোন করুন।</p><a class="emergency-call" href="tel:112"><span>112</span><strong data-en="India emergency helpline" data-bn="ভারতের জরুরি হেল্পলাইন">ভারতের জরুরি হেল্পলাইন</strong><b>CALL ↗</b></a><?php foreach($contacts as $contact): ?><a class="emergency-row" href="tel:<?= e(preg_replace('/[^0-9+]/','',$contact['phone'])) ?>"><span><strong data-en="<?= e($contact['label_en']) ?>" data-bn="<?= e($contact['label_bn'] ?: $contact['label_en']) ?>"><?= e($contact['label_bn'] ?: $contact['label_en']) ?></strong><small data-en="<?= e($contact['instructions_en']) ?>" data-bn="<?= e($contact['instructions_bn']) ?>"><?= e($contact['instructions_bn']) ?></small></span><b><?= e($contact['phone']) ?> ↗</b></a><?php endforeach; ?><small class="modal-warning" data-en="Please verify local contact numbers before relying on them. Do not use this website as a substitute for emergency services." data-bn="স্থানীয় নম্বর ব্যবহার করার আগে যাচাই করুন। জরুরি পরিষেবার বিকল্প হিসেবে এই ওয়েবসাইট ব্যবহার করবেন না।">স্থানীয় নম্বর ব্যবহার করার আগে যাচাই করুন। জরুরি পরিষেবার বিকল্প হিসেবে এই ওয়েবসাইট ব্যবহার করবেন না।</small></div></div>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>window.KALI_MAP_POINTS = <?= json_encode(array_merge(
  array_map(fn($p) => ['kind'=>'puja','name'=>$p['name_bn'] ?: $p['name_en'],'nameEn'=>$p['name_en'],'address'=>$p['address'],'mapUrl'=>$p['map_url'] ?? '', 'lat'=>is_numeric($p['lat'])?(float)$p['lat']:null,'lng'=>is_numeric($p['lng'])?(float)$p['lng']:null,'category'=>$p['category']], $pujas),
  array_map(fn($v) => ['kind'=>'service','name'=>$v['name_bn'] ?: $v['name_en'],'nameEn'=>$v['name_en'],'address'=>$v['address'],'lat'=>is_numeric($v['lat'])?(float)$v['lat']:null,'lng'=>is_numeric($v['lng'])?(float)$v['lng']:null,'category'=>$v['service_type'],'phone'=>$v['phone']], $services)
), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;</script>
<script src="assets/js/main.js"></script>
</body></html>
