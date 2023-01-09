
<?php

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "http://fictional-university.local/wp-json/wp/v2/sov_dirlist",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
//   CURLOPT_HTTPHEADER => array(
//     "x-rapidapi-host: unogsng.p.rapidapi.com",
//     "x-rapidapi-key: xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
//   ),
));

$services = curl_exec($curl);
$serviceArr = json_decode($services);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {

   foreach($serviceArr as $service){
//     echo '<pre>';
// print_r($service);
//    echo '</pre>';
   }

}
?>
<div class="listing-wrapper">
    <?php foreach($serviceArr as $service) : ?>
    <div class="service">
        <img src="" alt="">
        <h2><?php esc_html_e($service->title->rendered, 'sov-directory'); ?></h2>
        <?php echo ($service->content->rendered); ?>
    </div>
    <?php endforeach; ?>
</div>