<?php
// AI sederhana yang melakukan klasifikasi data menggunakan algoritma K-Nearest Neighbors (KNN).
// Dataset contoh: Berat (gram), Ukuran (cm), Jenis Buah
$dataset = [
    ['weight' => 150, 'size' => 7, 'type' => 'apple'],
    ['weight' => 200, 'size' => 8, 'type' => 'apple'],
    ['weight' => 120, 'size' => 6, 'type' => 'apple'],
    ['weight' => 250, 'size' => 9, 'type' => 'banana'],
    ['weight' => 300, 'size' => 10, 'type' => 'banana'],
    ['weight' => 270, 'size' => 9, 'type' => 'banana'],
];

// Fungsi untuk menghitung jarak Euclidean
function euclidean_distance($point1, $point2) {
    return sqrt(pow($point1['weight'] - $point2['weight'], 2) + pow($point1['size'] - $point2['size'], 2));
}

// Fungsi KNN
function knn_predict($dataset, $new_point, $k = 3) {
    $distances = [];

    // Hitung jarak dari data baru ke setiap data dalam dataset
    foreach ($dataset as $data_point) {
        $distance = euclidean_distance($new_point, $data_point);
        $distances[] = ['distance' => $distance, 'type' => $data_point['type']];
    }

    // Urutkan berdasarkan jarak terkecil
    usort($distances, function($a, $b) {
        return $a['distance'] <=> $b['distance'];
    });

    // Ambil K data terdekat
    $nearest_neighbors = array_slice($distances, 0, $k);

    // Hitung jumlah kemunculan setiap jenis
    $votes = [];
    foreach ($nearest_neighbors as $neighbor) {
        $type = $neighbor['type'];
        if (!isset($votes[$type])) {
            $votes[$type] = 0;
        }
        $votes[$type]++;
    }

    // Tentukan jenis dengan suara terbanyak
    arsort($votes); // Urutkan berdasarkan jumlah suara terbanyak
    return key($votes); // Kembalikan jenis dengan suara terbanyak
}

// Data baru untuk diklasifikasikan
$new_point = ['weight' => 280, 'size' => 20];

// Prediksi jenis buah
$predicted_type = knn_predict($dataset, $new_point);

echo "Jenis buah yang diprediksi: $predicted_type\n";
?>
