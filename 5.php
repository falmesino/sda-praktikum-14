<?php

  /**
   * 231232028 - Falmesino Abdul Hamid
   * Contoh program untuk mendeteksi anomali berdasarkan EMA:
   */

  /**
   * Fungsi untuk menghitung Exponential Moving Average (EMA)
   * dan mendeteksi anomali jika nilai sensor menyimpang terlalu jauh.
   */

  function detectAnomaliesEMA($data, $alpha = 0.2, $threshold = 2.0) {
    $ema = $data[0]; // inisialisasi dengan data pertama
    $anomalies = [];
    // Simpan nilai EMA untuk analisis standar deviasi sederhana
    $emaValues = [$ema];

    // Hitung EMA untuk setiap data
    for ($i = 1; $i < count($data); $i++) {
      $ema = $alpha * $data[$i] + (1 - $alpha) * $ema;
      $emaValues[] = $ema;
    }

    // Hitung deviasi sederhana: rata-rata absolute difference antara
    // data dan EMA
    $sumDiff = 0;
    foreach ($data as $i => $value) {
      $sumDiff += abs($value - $emaValues[$i]);
    }
    $meanDiff = $sumDiff / count($data);

    // Deteksi anomali: jika deviasi absolute melebihi threshold * meanDiff
    foreach ($data as $i => $value) {
      if (abs($value - $emaValues[$i]) > $threshold * $meanDiff) {
        $anomalies[$i] = $value;
      }
    }
    return $anomalies;
  }

  // Contoh data sensor (misalnya, suhu) secara real-time
  $sensorData = [22.5, 22.7, 22.6, 22.8, 23.0, 23.1, 22.9, 23.2, 25.0, 23.0, 22.8, 22.7, 22.5];
  
  $anomalies = detectAnomaliesEMA($sensorData, 0.3, 2.0);

  echo "Data Sensor:\n";
  print_r($sensorData);
  echo "\nAnomali Teridentifikasi (indeks => nilai):\n";
  print_r($anomalies);

  /**
   * Analisis:
   * - Setiap data diproses sekali: O(n).
   * - Ruang yang digunakan konstan (O(1) tambahan, meskipun array emaValues
   *   O(n) dapat dioptimalkan jika hanya nilai terakhir yang disimpan).
   * - Cocok untuk aplikasi IoT dengan data stream secara real-time.
   */

?>